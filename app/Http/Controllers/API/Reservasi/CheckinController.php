<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
class CheckinController extends Controller
{

    public function filterTgl($tgl_awal, $tgl_akhir){
      $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      ->whereBetween('bookings.tgl_checkin', [$tgl_awal, $tgl_akhir])
                      ->orderBy('bookings.tgl_checkin', 'desc')
                      ->get();

      return response()->json($bookings);
    }

    public function loadData(){
      $tgl = Carbon::now();
      $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      // ->whereDate('bookings.tgl_checkin', '=', $tgl)
                      ->orderBy('bookings.tgl_checkin', 'desc')
                      ->get();

      return response()->json($bookings);
    }
}
