<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Booking;
use App\BookingRoom;
use Carbon\Carbon;
use App\BookingCharges;
use App\Charge;
use App\Tagihan;

class CheckoutController extends Controller
{
  public function prosesCheckout($kode_booking){

    DB::beginTransaction();
    try {
      $checkin = Booking::where('kode_booking', $kode_booking)->first();

      $checkin->update([
        'status' => '3'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }

    try {
      $rooms = BookingRoom::where('kode_booking', $kode_booking)->get();

      foreach ($rooms as $room) {
        $room->delete();
      }

    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }


    try {
      $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();
      $tagihan->update([
        'terbayarkan' => $tagihan->terbayarkan + $tagihan->hutang,
        'hutang' => '0',
        'status' => '3',
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      throw $e;
    }


    DB::commit();

    return response()->json([
      'msg' => 'Checkin berhasil'
    ]);
  }

  public function filterTgl($tgl_awal, $tgl_akhir){
    $bookings = DB::table('bookings')
                    ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                    ->select(DB::raw("bookings.kode_booking, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                    (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted'
                    WHEN (bookings.status = 2) THEN 'Checkin' WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status=4) THEN 'Checkout' END) as status"))
                    ->whereBetween('bookings.tgl_checkin', [$tgl_awal, $tgl_akhir])
                    ->whereIn('bookings.status', ['3', '4'])
                    ->orderBy('bookings.tgl_checkin', 'desc')
                    ->get();

    return response()->json($bookings);
  }

  public function loadData(){
    $tgl = Carbon::now();
    $bookings = DB::table('bookings')
                    ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                    ->select(DB::raw("bookings.kode_booking, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                    (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted'
                    WHEN (bookings.status = 2) THEN 'Checkin' WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status=4) THEN 'Checkout' END) as status"))
                    // ->whereDate('bookings.tgl_checkin', '=', $tgl)
                    ->whereIn('bookings.status', ['3', '4'])
                    ->orderBy('bookings.tgl_checkin', 'desc')
                    ->get();

    return response()->json($bookings);
  }
}
