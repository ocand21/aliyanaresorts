<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
class InhouseController extends Controller
{
    public function index()
    {
        // $bookings = DB::table('bookings')
        //                 ->join('pelanggan_wig', 'pelanggan_wig.id', 'bookings.id_pelanggan')
        //                 ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, pelanggan_wig.nama, pelanggan_wig.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
        //                 (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
        //                 WHEN (bookings.status = 3) THEN 'Inhouse' END) as status"))
        //                 ->where('bookings.status', '=', '3')
        //                 ->get();

        $date = Carbon::now()->format('Y-m-d');

        $bookings = DB::table('bookings')
                          ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                          ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                          ->join('booking_rooms', 'booking_rooms.kode_booking', 'bookings.kode_booking')
                          ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                          ->select(DB::raw("booking_rooms.no_room, bookings.kode_booking, tipe_kamar.tipe, bookings.tgl_checkin, booking_rooms.nama_penghuni,
                          booking_rooms.jml_penghuni, bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                          (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                          WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status"))
                          ->whereIn('bookings.status', [['3'],['4']])
                          ->groupBy('booking_rooms.nama_penghuni')
                          ->orderBy('bookings.tgl_checkin', 'desc')
                          ->get();

        foreach($bookings as $booking){
          if ($booking->tgl_checkout == $date) {
            
            DB::beginTransaction();
            try {
              DB::table('bookings')->where('tgl_checkout', $date)->update([
                'status' => '4'
              ]);
            } catch (\Exception $e) {
              throw $e;
              DB::rollback();
            }
            DB::commit();

          }

        }

        return response()->json($bookings);
    }
}
