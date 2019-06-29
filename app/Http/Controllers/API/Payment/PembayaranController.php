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
    public function konfirm($id){

      DB::beginTransaction();
      try {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->status = '1';

        $pembayaran->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $tagihan = Tagihan::where('kode_booking', $pembayaran->kode_booking)->first();
        $tagihan->terbayarkan = $tagihan->terbayarkan + $pembayaran->jumlah;
        $tagihan->hutang = $tagihan->terbayarkan - $tagihan->total_tagihan;

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
