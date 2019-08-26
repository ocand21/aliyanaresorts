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
        'status' => '1',
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {
      $tagihan = Tagihan::where('kode_booking', $request->kode_booking)->first();
      if ($tagihan->total_tagihan == $request->jml_bayar) {
        $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
        $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
        $tagihan->id_metode = $request->id_metode;
        $tagihan->status = '1';
        $tagihan->save();
      } else {
        $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
        $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
        $tagihan->id_metode = $request->id_metode;
        $tagihan->status = '2';
        $tagihan->save();
      }



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
      // $half = $tagihan->total_tagihan * 0.5;
      // dd($half);
      if ($tagihan->total_tagihan == $request->jml_bayar) {
        $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
        $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
        $tagihan->id_metode = $metode->id;
        $tagihan->status = '1';
        $tagihan->save();
      } else {
        $tagihan->terbayarkan = $tagihan->terbayarkan + $request->jml_bayar;
        $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
        $tagihan->id_metode = $metode->id;
        $tagihan->status = '2';
        $tagihan->save();
      }

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
        $pembayaran = TransferPayment::findOrFail($id);
        $pembayaran->status = '1';
        $pembayaran->id_users = $user->id;
        $pembayaran->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $tagihan = Tagihan::where('kode_booking', $pembayaran->kode_booking)->first();
        $tagihan->terbayarkan = $tagihan->terbayarkan + $pembayaran->jml_bayar;
        $tagihan->hutang = $tagihan->total_tagihan - $tagihan->terbayarkan;
        $tagihan->id_metode = $pembayaran->id_metode;
        $tagihan->status = '1';
        $tagihan->save();

      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $date = Carbon::now()->format('Y-m-d');
        $booking = Booking::where('kode_booking', $pembayaran->kode_booking)->first();

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
      $pembayaran = DB::table('transfer_payments')
                        ->join('metode_pembayaran', 'metode_pembayaran.id', 'transfer_payments.id_metode')
                        ->select(DB::raw("transfer_payments.id, transfer_payments.image, transfer_payments.kode_booking, transfer_payments.nama_pemilik_rekening as nama, transfer_payments.no_rekening,
                        metode_pembayaran.bank, metode_pembayaran.atas_nama, metode_pembayaran.no_rekening as no_rekening_tujuan,
                        transfer_payments.jml_bayar, transfer_payments.tgl_transfer, (CASE WHEN (transfer_payments.status = 0) THEN 'Menunggu Konfirmasi' ELSE 'Dikonfirmasi' END) as status"))
                        ->where('transfer_payments.status', '0')
                        ->orderBy('transfer_payments.id', 'desc')
                        ->get();

      return response()->json($pembayaran);
    }
}
