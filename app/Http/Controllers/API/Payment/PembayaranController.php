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
class PembayaranController extends Controller
{
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
