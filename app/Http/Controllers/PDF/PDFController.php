<?php

namespace App\Http\Controllers\PDF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use App\KonfigWeb;
class PDFController extends Controller
{
    public function invoice($kode_booking){
      // $pdf = PDF::loadView('pdf.invoice')->setPaper('a4','potrait')->setOptions(['defaultFont' => 'sans-serif']);
      $konfig = KonfigWeb::first();
      // return $pdf->stream('invoice.pdf');
      $booking = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, bookings.jml_kamar as jmlKamar, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      ->first();

                      $rooms = DB::table('booking_rooms')
                                  ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                                  ->select(DB::raw("booking_rooms.no_room, tipe_kamar.tipe, tipe_kamar.kapasitas, booking_rooms.jml_tamu, tipe_kamar.harga"))
                                  ->where('booking_rooms.kode_booking', $kode_booking)
                                  ->get();

      $subtotal = DB::table('booking_rooms')
                      ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                      ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                      ->select(DB::raw("SUM(harga) as sub_total"))
                      ->where('booking_rooms.kode_booking', $kode_booking)
                      ->first();

                      $tagihan = DB::table('tagihan')
                                    ->select(DB::raw("total_tagihan, terbayarkan, hutang"))
                                    ->where('kode_booking', $kode_booking)
                                    ->first();

      $tgl1 = Carbon::parse($booking->tgl_checkin);
      $tgl2 = Carbon::parse($booking->tgl_checkout);
      $durasi = $tgl1->diffInDays($tgl2);

      // $total = $subtotal->sub_total * $durasi;
      // dd($subtotal);
      return view('pdf.invoice', compact('booking', 'rooms', 'subtotal', 'durasi', 'tagihan', 'konfig'));
    }
}
