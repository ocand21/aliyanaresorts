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
class CheckinController extends Controller
{

    public function loadTagihan($kode_booking){
      $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();

      return response()->json($tagihan);
    }

    public function loadCharges($kode_booking){
      $charge = Charge::where('kode_booking', $kode_booking)->get();

      return response()->json($charge);
    }

    public function applyCharges(Request $request, $kode_booking){
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

    public function prosesCheckin($kode_booking){

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
        $tagihan = Tagihan::where('kode_booking', $kode_booking)->first();
        $tagihan->update([
          'terbayarkan' => $tagihan->terbayarkan + $tagihan->hutang,
          'hutang' => '0',
          'status' => '1',
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

    public function detilCheckin($kode_booking){
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
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted'
                      WHEN (bookings.status = 2) THEN 'Checkin' END) as status"))
                      // ->whereDate('bookings.tgl_checkin', '=', $tgl)
                      ->whereIn('bookings.status', ['1', '2'])
                      ->orderBy('bookings.tgl_checkin', 'desc')
                      ->get();

      return response()->json($bookings);
    }
}
