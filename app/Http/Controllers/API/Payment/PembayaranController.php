<?php

namespace App\Http\Controllers\API\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Notifications\KonfirmasiPembayaran;

use App\Pembayaran;
use App\Tagihan;
use App\Booking;
use App\Pelanggan;
use App\MetodePembayaran;

use App\Model\Payment\CashPayment;
use App\Model\Payment\TransferPayment;

use Carbon\Carbon;

class PembayaranController extends Controller
{

  public function transferPayment(Request $request){
    $user = auth('api')->user();
    // dd($user);

    $this->validate($request,[
      'kode_booking' => 'required',
      'jml_bayar' => 'required',
      'id_metode' => 'required',
      'nama_pemilik_rekening' => 'required',
      'no_rekening' => 'required',
      'tgl_transfer' => 'required',
      'jml_bayar' => 'required',
    ]);

    // dd($request->jml_bayar);

    DB::beginTransaction();
    try {
      $transfer = TransferPayment::create([
        'kode_booking' => $request->kode_booking,
        'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
        'id_metode' => $request->id_metode,
        'no_rekening' => $request->no_rekening,
        'tgl_transfer' => $request->tgl_transfer,
        'jml_bayar' => $request->jml_bayar,
        'id_users' => $user->id,
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {
      $tagihan = Tagihan::where('kode_booking', $request->kode_booking)->first();
      $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
      $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
      $tagihan->id_metode = $request->id_metode;
      $tagihan->status = '3';
      $tagihan->save();

    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {
      $date = Carbon::now()->format('Y-m-d');
      $booking = Booking::where('kode_booking', $request->kode_booking)->first();

      if ($booking->tgl_checkin == $date) {
        $booking->update([
          'status' => '2',
        ]);
      } else {
        $booking->update([
          'status' => '1',
        ]);
      }

      // else {
      //   $booking->update([
      //     'status' => '1',
      //   ]);
      // }
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    DB::commit();

    return response()->json([
      'msg' => 'Pembayaran berhasil!'
    ]);
  }

  public function cashPayment(Request $request){
    $user = auth('api')->user();
    // dd($user);

    $this->validate($request,[
      'kode_booking' => 'required',
      'jml_bayar' => 'required',
    ]);

    // dd($request->jml_bayar);

    DB::beginTransaction();
    try {
      $cash = CashPayment::create([
        'kode_booking' => $request->kode_booking,
        'jml_bayar' => $request->jml_bayar,
        'id_users' => $user->id,
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {
      $metode = MetodePembayaran::where('bank', 'CASH')->first();
      $tagihan = Tagihan::where('kode_booking', $request->kode_booking)->first();
      $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
      $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
      $tagihan->id_metode = $metode->id;
      $tagihan->status = '3';
      $tagihan->save();

    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {

        $date = Carbon::now()->format('Y-m-d');
        $booking = Booking::where('kode_booking', $request->kode_booking)->first();

        if ($booking->tgl_checkin == $date) {
          $booking->update([
            'status' => '2',
          ]);
        } else {
          $booking->update([
            'status' => '1',
          ]);
        }

    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    DB::commit();

    return response()->json([
      'msg' => 'Pembayaran berhasil!'
    ]);

  }

  public function detilPembayaran($kode_booking){
    $pembayaran = DB::table('pembayaran')
                      ->join('users', 'users.id', 'pembayaran.id_user')
                      ->join('metode_pembayaran', 'metode_pembayaran.id', 'pembayaran.id_metode')
                      ->select(DB::raw("metode_pembayaran.bank, metode_pembayaran.atas_nama, pembayaran.tgl_transfer, pembayaran.jumlah, users.name as dikonfirmasi"))
                      ->where([['pembayaran.kode_booking', $kode_booking],['pembayaran.status', '1']])
                      ->get();

    return response()->json($pembayaran);
  }

    public function konfirm($id){
      $user = auth('api')->user();

      DB::beginTransaction();
      try {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = '1';
        $pembayaran->id_user = $user->id;
        $pembayaran->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $tagihan = Tagihan::where('kode_booking', $pembayaran->kode_booking)->first();
        $tagihan->terbayarkan = $tagihan->terbayarkan + $pembayaran->jumlah;
        $tagihan->hutang = $tagihan->terbayarkan - $tagihan->total_tagihan;
        if ($pembayaran->jumlah == $tagihan->total_tagihan) {
          $tagihan->status = '1';
        } elseif ($pembayaran->jumlah < $tagihan->total_tagihan ) {
          $tagihan->status = '2';
        }

        $tagihan->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $booking = Booking::where('kode_booking', $pembayaran->kode_booking)->first();
        $booking->status = '1';
        $booking->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $pelanggan = Pelanggan::where('id', $booking->id_pelanggan)->first();
        $pelanggan->notify(new KonfirmasiPembayaran($booking->kode_booking));
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }


      DB::commit();

      return response()->json([
        'msg' => 'Pembayaran dikonfirmasi!'
      ]);
    }

    public function index(){
      $pembayaran = DB::table('pembayaran')
                        ->join('metode_pembayaran', 'metode_pembayaran.id', 'pembayaran.id_metode')
                        ->select(DB::raw("pembayaran.id, pembayaran.kode_booking, pembayaran.nama_pemilik_rekening as nama, pembayaran.no_rekening,
                        metode_pembayaran.bank, metode_pembayaran.atas_nama, metode_pembayaran.no_rekening as no_rekening_tujuan,
                        pembayaran.jumlah, pembayaran.tgl_transfer, (CASE WHEN (pembayaran.status = 0) THEN 'Menunggu Konfirmasi' ELSE 'Dikonfirmasi' END) as status"))
                        ->where('pembayaran.status', '0')
                        ->orderBy('pembayaran.id', 'desc')
                        ->get();

      return response()->json($pembayaran);
    }
}
