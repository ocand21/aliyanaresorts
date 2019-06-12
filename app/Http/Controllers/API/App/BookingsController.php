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
    $tipe = $request->id_tipe;

    $tgl1 = Carbon::parse($tgl_checkin)->format("d M Y");
    $tgl2 = Carbon::parse($tgl_checkout)->format("d M Y");

    if ($tipe == "0") {
      $kamar = Kamar::with('booking', 'tipe')->whereHas('booking', function($q) use ($tgl_checkin, $tgl_checkout){
        $q->where(function($q2) use ($tgl_checkin, $tgl_checkout){
          $q2->where('tgl_checkin', '>=', $tgl_checkout)
             ->orWhere('tgl_checkout', '<=', $tgl_checkin);
        })->where([['status_temp', '=', '0']]);
      })->orWhereDoesntHave('booking')->where('status_temp', '=', '0')->get();
    } else {
      $kamar = Kamar::with('booking', 'tipe')->whereHas('booking', function($q) use ($tgl_checkin, $tgl_checkout, $tipe){
        $q->where(function($q2) use ($tgl_checkin, $tgl_checkout){
          $q2->where('tgl_checkin', '>=', $tgl_checkout)
             ->orWhere('tgl_checkout', '<=', $tgl_checkin);
        })->where([['status_temp', '=', '0'],['id_tipe', $tipe]]);
      })->orWhereDoesntHave('booking')->where([['id_tipe', $tipe],['status_temp', '=', '0']])->get();
    }


    $jml = 2 * $kamar->count();
    $count = $kamar->count();

    if ($jmlKamar > $jml) {
      return response()->json([
        'kamar' => null,
        'msg' => 'Kamar tidak tersedia. Silakan ganti tanggal atau kurangi jumlah.'
      ]);
    }
    else {
      return response()->json([
        'kamar' => $kamar,
        'msg' => $count . ' Kamar tersedia pada ' . $tgl1 . ' sd ' . $tgl2 . ' untuk ' . $jmlKamar . ' orang tamu',
      ]);
    }
  }
}
