<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InhouseController extends Controller
{
    public function index(){
      $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                      WHEN (bookings.status = 3) THEN 'Inhouse' END) as status"))
                      ->where('bookings.status', '=', '3')
                      ->get();

      return response()->json($bookings);
    }
}
