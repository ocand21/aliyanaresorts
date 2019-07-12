<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Veritrans_Config;
use Veritrans_Snap;
use Veritrans_Notification;
use Illuminate\Support\Facades\DB;
use App\Pembayaran;
use App\MetodePembayaran;

use Image;
use Carbon\Carbon;
use Session;
class PembayaranController extends Controller
{

  

    public function getForm($kode_booking){
      $booking = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, bookings.jml_kamar as jmlKamar, bookings.id_pelanggan, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.created_at, bookings.total, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      ->where('bookings.kode_booking', $kode_booking)
                      ->first();

      $pelanggan = DB::table('pelanggan')
                       ->select(DB::raw("id, nama, email"))
                       ->where('id', $booking->id_pelanggan)
                       ->first();

      $metode = MetodePembayaran::get();

      return view('payment.form', compact('booking', 'pelanggan', 'metode'));
    }

    public function store(Request $request){
      $request->validate([
        'kode_booking' => 'required',
        'tgl_transfer' => 'required',
        'nama_pemiliki_rekening' => 'required',
        'no_rekening' => 'required',
        'id_metode' => 'required',
        'jumlah' => 'required',
      ]);

      DB::beginTransaction();
      try {
        $pembayaran = new Pembayaran();

        $pembayaran->kode_booking = $request->kode_booking;
        $pembayaran->id_pelanggan = $request->id_pelanggan;
        $pembayaran->tgl_transfer = $request->tgl_transfer;
        $pembayaran->nama_pemilik_rekening =$request->nama_pemiliki_rekening;
        $pembayaran->no_rekening = $request->no_rekening;
        $pembayaran->id_metode = $request->id_metode;
        $pembayaran->jumlah = $request->jumlah;
        $pembayaran->status = '0';

        $pembayaran->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      Session::flash('flash_message', 'Berhasil! Silakan tunggu email pemberitahuan apabila pembayaran telah kami terima');

      return redirect()->back();

    }
}
