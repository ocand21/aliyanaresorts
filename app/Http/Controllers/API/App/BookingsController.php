<?php

namespace App\Http\Controllers\API\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Notifications\InvoiceBooking;
use App\Booking;
use App\Kamar;
use App\BookingTemp;
use App\BookingRoom;
use App\Pelanggan;
use App\TipeKamar;
use Mail;

class BookingsController extends Controller
{
  public function cekKamar(Request $request){

    $this->validate($request,[
      'tgl_checkin' => 'required',
      'tgl_checkout' => 'required',
      'jml_tamu' => 'required',
    ]);

    $tgl_checkin = $request->tgl_checkin;
    $tgl_checkout = $request->tgl_checkout;
    $jmlKamar = $request->jml_tamu;
    $tipe = $request->tipe;

    $tgl1 = Carbon::parse($tgl_checkin)->format("d M Y");
    $tgl2 = Carbon::parse($tgl_checkout)->format("d M Y");

    if ($tipe == "0") {
      $kamar = DB::table('kamar')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                  ->join('foto_kamar', 'foto_kamar.id_tipe', 'tipe_kamar.id')
                  ->select(DB::raw("kamar.id, kamar.no_room, foto_kamar.lokasi as foto, kamar.id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga"))
                  ->whereNotIn('kamar.no_room', function($q) use ($tgl_checkin, $tgl_checkout){
                    $q->select('no_room')->from('booking_rooms')
                      ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                      // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                      // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout]);
                      ->where([['bookings.tgl_checkin', '<=', $tgl_checkout],['bookings.tgl_checkout', '>=', $tgl_checkin]]);
                  })->where([['status_temp', '0']])
                  ->groupBy('kamar.no_room')
                  ->get();

      // $kamar = DB::table('kamar')
      //             ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
      //             ->join('booking_rooms', 'booking_rooms.no_room', 'kamar.no_room')
      //             ->select(DB::raw("kamar.id, tipe.tipe, tipe.kapasitas, tipe.harga"))
      //             ->whereNotIn("kamar.id")

    } else {
      // $kamar = Kamar::with('booking', 'tipe_kamar')->whereHas('booking', function($q) use ($tgl_checkin, $tgl_checkout){
      //   $q->where(function($q2) use ($tgl_checkin, $tgl_checkout){
      //     $q2->where('tgl_checkin', '>=', $tgl_checkout)
      //        ->orWhere('tgl_checkout', '<=', $tgl_checkin);
      //   })->where([['status_temp', '=', '0']]);
      // })->orWhereDoesntHave('booking')->where([['status_temp', '=', '0']])
      // ->whereHas('tipe_kamar', function($q3) use ($tipe){
      //   $q3->where('tipe', $tipe);
      // })->get();


      $kamar = DB::table('kamar')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                  ->join('foto_kamar', 'foto_kamar.id_tipe', 'tipe_kamar.id')
                  ->select(DB::raw("kamar.id, foto_kamar.lokasi as foto, kamar.no_room, kamar.id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga"))
                  ->whereNotIn('kamar.no_room', function($q) use ($tgl_checkin, $tgl_checkout){
                    $q->select('no_room')->from('booking_rooms')
                      ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                      // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                      // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout]);
                      ->where([['bookings.tgl_checkin', '<=', $tgl_checkout],['bookings.tgl_checkout', '>=', $tgl_checkin]]);
                  })->where([['tipe_kamar.tipe', $tipe], ['status_temp', '0']])
                  ->groupBy('kamar.no_room')
                  ->get();

      // $kamar = Kamar::with('booking', 'tipe_kamar')->whereHas('booking', function($q) use ($tgl_checkin, $tgl_checkout, $tipe){
      //   $q->where(function($q2) use ($tgl_checkin, $tgl_checkout){
      //     $q2->where('tgl_checkin', '>=', $tgl_checkout)
      //        ->orWhere('tgl_checkout', '<=', $tgl_checkin);
      //   })->where([['status_temp', '=', '0'],['id_tipe', $tipe]]);
      // })->orWhereDoesntHave('booking')->where([['id_tipe', $tipe],['status_temp', '=', '0']])->get();
    }


    $jml = 2 * $kamar->count();
    $count = $kamar->count();

    if ($jmlKamar > $jml) {
      return response()->json([
        'kamar' => $kamar,
      ]);
    }
    else {
      return response()->json([
        'kamar' => $kamar,
      ]);
    }
  }
}
