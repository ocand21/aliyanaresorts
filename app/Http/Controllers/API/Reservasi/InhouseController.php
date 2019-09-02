<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\BookingRoom;
class InhouseController extends Controller
{
    public function konfirmPindah(Request $request, $no_room){
      $old_no = $request->old_no;

      $kamar = BookingRoom::where('no_room', $old_no)->first();
      $kamar->update([
        'no_room' => $no_room,
      ]);

      return response()->json([
        'msg' => 'Simpan berhasil',
      ]);
    }

    public function pindahKamar($no_room){
      $inhouse = DB::table('booking_rooms')
                      ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                      ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                      ->select(DB::raw("booking_rooms.no_room, kamar.id_tipe, booking_rooms.tgl_checkin, booking_rooms.tgl_checkout"))
                      ->where('booking_rooms.no_room', $no_room)
                      ->first();

      $tgl_checkin = $inhouse->tgl_checkin;
      $tgl_checkout = $inhouse->tgl_checkout;
      $tgl1 = Carbon::parse($tgl_checkin)->format("d M Y");
      $tgl2 = Carbon::parse($tgl_checkout)->format("d M Y");

      $kamar = DB::table('kamar')
                ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                ->select(DB::raw("kamar.id, kamar.no_room, kamar.id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas"))
                ->whereNotIn('kamar.no_room', function ($q) use ($tgl_checkin, $tgl_checkout) {
                    $q->select('no_room')->from('booking_rooms')
                    ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                    // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                    // ->whereBetween('bookings.tgl_checkin', [$tgl_checkout, $tgl_checkin]);
                    ->where([['bookings.tgl_checkin', '<=', $tgl_checkout],['bookings.tgl_checkout', '>=', $tgl_checkin]]);
                })
                ->where([['id_tipe', $inhouse->id_tipe],['status_temp', '0']])
                ->get();

      $count = $kamar->count();

      return response()->json([
        'avKamar' => $kamar,
        'msg' => $count . ' Kamar tersedia pada ' . $tgl1 . ' sd ' . $tgl2 . ' ',
        'oldRoom' => $inhouse,
    ]);
    }

    public function detilKamar($no_room){
      $dtl = DB::table('booking_rooms')
                  ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                  ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                  ->select(DB::raw("booking_rooms.no_room, booking_rooms.nama_penghuni, booking_rooms.jml_penghuni, bookings.kode_booking"))
                  ->where('booking_rooms.no_room', $no_room)
                  ->first();

      return response()->json($dtl);
    }

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
                          (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                          WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status"))
                          ->whereIn('bookings.status', [['2']])
                          ->groupBy('booking_rooms.nama_penghuni')
                          ->orderBy('bookings.tgl_checkin', 'desc')
                          ->get();


        return response()->json($bookings);
    }
}
