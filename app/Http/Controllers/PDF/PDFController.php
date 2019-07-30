<?php

namespace App\Http\Controllers\PDF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;
use App\KonfigWeb;
use App\MetodePembayaran;
use App\Charge;
class PDFController extends Controller
{
    public function invoice($kode_booking){
      // $pdf = PDF::loadView('pdf.invoice')->setPaper('a4','potrait')->setOptions(['defaultFont' => 'sans-serif']);
      $konfig = KonfigWeb::first();
      // return $pdf->stream('invoice.pdf');
      $booking = DB::table('bookings')
                      ->join('pelanggan_wig', 'pelanggan_wig.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->join('users', 'bookings.id_users', 'users.id')
                      ->leftJoin('metode_pembayaran', 'metode_pembayaran.id', 'tagihan.id_metode')
                      ->select(DB::raw("bookings.kode_booking, pelanggan_wig.no_identitas, pelanggan_wig.tipe_identitas, pelanggan_wig.nama, pelanggan_wig.email, pelanggan_wig.no_telepon, pelanggan_wig.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, tagihan.total_tagihan, tagihan.terbayarkan, tagihan.hutang,
                      users.name as created_by, bookings.created_at, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status,
                      metode_pembayaran.bank"))
                      ->where('bookings.kode_booking', $kode_booking)
                      ->first();

      // dd($booking);
                      // $rooms = DB::table('booking_rooms')
                      //             ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                      //             ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                      //             ->select(DB::raw("booking_rooms.no_room, tipe_kamar.tipe, tipe_kamar.kapasitas, booking_rooms.jml_tamu, tipe_kamar.harga"))
                      //             ->where('booking_rooms.kode_booking', $kode_booking)
                      //             ->get();

      $rooms = DB::table('booking_types')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                  ->select(DB::raw("tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga, booking_types.jml_kamar"))
                  ->where('booking_types.kode_booking', $kode_booking)
                  ->get();

      // $subtotal = DB::table('booking_rooms')
      //                 ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
      //                 ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
      //                 ->select(DB::raw("SUM(harga) as sub_total"))
      //                 ->where('booking_rooms.kode_booking', $kode_booking)
      //                 ->first();

                      $tagihan = DB::table('tagihan')
                                    ->select(DB::raw("total_tagihan, terbayarkan, hutang"))
                                    ->where('kode_booking', $kode_booking)
                                    ->first();

      $tgl1 = Carbon::parse($booking->tgl_checkin);
      $tgl2 = Carbon::parse($booking->tgl_checkout);
      $durasi = $tgl1->diffInDays($tgl2);

      $metode = MetodePembayaran::whereNotIn('bank', ['CASH', 'CREDIT CARD'])->orderBy('id', 'asc')->get();

      $charges = Charge::where('kode_booking', $kode_booking)->get();

      // $total = $subtotal->sub_total * $durasi;
      // dd($subtotal);
      return view('pdf.invoice', compact('booking', 'durasi', 'rooms', 'tagihan', 'metode', 'konfig', 'charges'));
    }
}
