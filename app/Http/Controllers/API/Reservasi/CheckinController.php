<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Booking;
use Carbon\Carbon;
use App\BookingCharges;
use App\Charge;
use App\Tagihan;
use App\BookingRoom;

class CheckinController extends Controller
{
    public function hapusRoom($id)
    {
        $room = BookingRoom::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $room->delete();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return response()->json([
        'msg' => 'Berhasil dihapus',
      ]);
    }

    public function updateOpsional(Request $request)
    {
        $room = BookingRoom::where('id', $request->id)->first();

        DB::beginTransaction();
        try {
            $room->nama_penghuni = $request->nama_penghuni;
            $room->jml_penghuni = $request->jml_penghuni;
            $room->save();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return response()->json([
        'msg' => 'Berhasil diupdate',
      ]);
    }

    public function opsionalKamar($id_room)
    {
        $opsional = BookingRoom::where('id', $id_room)->first();

        // dd($opsional);

        return response()->json($opsional);
    }

    public function checkinRoom($kode_booking)
    {
        $room = DB::table('booking_rooms')
                  ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                  ->select(DB::raw("booking_rooms.id, kamar.no_room, tipe_kamar.tipe, booking_rooms.nama_penghuni, booking_rooms.jml_penghuni"))
                  ->where('booking_rooms.kode_booking', $kode_booking)
                  ->get();

        return response()->json($room);
    }

    public function pilihKamar(Request $request, $no_room)
    {
        DB::beginTransaction();
        try {
            $room = BookingRoom::create([
          'kode_booking' => $request->kode_booking,
          'no_room' => $no_room,
          'tgl_checkin' => $request->tgl_checkin,
          'tgl_checkout' => $request->tgl_checkout,
        ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return response()->json([
        'msg' => 'Kamar berhasil dipilih',
      ]);
    }

    public function cekKamar(Request $request, $id_tipe)
    {
        $tgl_checkin = $request->tgl_checkin;
        $tgl_checkout = $request->tgl_checkout;

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
                  ->where([['id_tipe', $id_tipe],['status_temp', '0']])
                  ->get();

        $count = $kamar->count();

        return response()->json([
          'avKamar' => $kamar,
          'msg' => $count . ' Kamar tersedia pada ' . $tgl1 . ' sd ' . $tgl2 . ' ',
      ]);
    }

    public function loadTagihan($kode_booking)
    {
        $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();

        return response()->json($tagihan);
    }

    public function loadCharges($kode_booking)
    {
        $charge = Charge::where('kode_booking', $kode_booking)->get();

        return response()->json($charge);
    }

    public function applyCharges(Request $request, $kode_booking)
    {
        DB::beginTransaction();
        try {
            $request->validate([
          'nama_charge' => 'required',
          'jumlah_persen' => 'required',
          'jumlah_rupiah' => 'required',
          'keterangan' => 'required',
        ]);

            $charge = Charge::create([
          'kode_booking' => $kode_booking,
          'nama_charge' => $request->nama_charge,
          'jumlah_persen' => $request->jumlah_persen,
          'jumlah_rupiah' => $request->jumlah_rupiah,
          'keterangan' => $request->keterangan,
        ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();
            $tagihan->update([
            'total_tagihan' => $tagihan->total_tagihan + $request->jumlah_rupiah,
            'hutang' => $tagihan->total_tagihan + $request->jumlah_rupiah - $tagihan->terbayarkan,
            'status' => '2',
          ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        DB::commit();

        return response()->json([
        'msg' => 'Berhasil'
      ]);
    }

    public function prosesCheckin($kode_booking)
    {
        DB::beginTransaction();
        try {
          $checkin = Booking::where('kode_booking', $kode_booking)->first();
          $checkin->update([
            'status' => '2'
          ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // try {
        //     $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();
        //     $tagihan->update([
        //   'terbayarkan' => $tagihan->terbayarkan + $tagihan->hutang,
        //   'hutang' => '0',
        //   'status' => '1',
        // ]);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     throw $e;
        // }


        DB::commit();

        return response()->json([
        'msg' => 'Checkin berhasil'
      ]);
    }

    public function detilCheckin($kode_booking)
    {
        $detil = DB::table('bookings')
                ->select(DB::raw("total, tgl_checkin, tgl_checkout"))
                ->where('kode_booking', $kode_booking)
                ->first();

        $tgl1 = Carbon::parse($detil->tgl_checkin);
        $tgl2 = Carbon::parse($detil->tgl_checkout);
        $durasi = $tgl1->diffInDays($tgl2);

        return response()->json([
        'detil' => $detil,
        'durasi' => $durasi
      ]);
    }

    public function filterTgl($tgl_awal, $tgl_akhir)
    {
        $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                      ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                      ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                      bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                      WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status"))
                      ->whereIn('bookings.status', ['2'])
                      ->whereBetween('bookings.tgl_checkin', [$tgl_awal, $tgl_akhir])
                      ->orderBy('bookings.tgl_checkin', 'desc')
                      ->get();

        return response()->json($bookings);
    }

    public function loadData()
    {
        $tgl = Carbon::now();
        // $bookings = DB::table('bookings')
        //                 ->join('pelanggan_wig', 'pelanggan_wig.id', 'bookings.id_pelanggan')
        //                 ->select(DB::raw("bookings.kode_booking, pelanggan_wig.nama, pelanggan_wig.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
        //                 (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted'
        //                 WHEN (bookings.status = 2) THEN 'Checkin' END) as status"))
        //                 // ->whereDate('bookings.tgl_checkin', '=', $tgl)
        //                 ->whereIn('bookings.status', ['1', '2'])
        //                 ->orderBy('bookings.tgl_checkin', 'desc')
        //                 ->get();


        $bookings = DB::table('bookings')
                    ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                    ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                    ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                    ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                    ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                    bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                    (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                    WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status"))
                    ->whereIn('bookings.status', ['2'])
                    ->orderBy('bookings.tgl_checkin', 'asc')
                    ->get();


        return response()->json($bookings);
    }
}
