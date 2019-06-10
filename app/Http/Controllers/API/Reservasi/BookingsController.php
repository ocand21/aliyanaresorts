<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Booking;
use App\Kamar;
use App\BookingTemp;
use App\BookingRoom;
use App\Pelanggan;
class BookingsController extends Controller
{

    public function detilKamar($kode_booking){
      $rooms = DB::table('booking_rooms')
                  ->join('kamar', 'kamar.no_room', 'booking_rooms.no_room')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                  ->select(DB::raw("booking_rooms.no_room, tipe_kamar.tipe, tipe_kamar.kapasitas, booking_rooms.jml_tamu, tipe_kamar.harga"))
                  ->where('booking_rooms.kode_booking', $kode_booking)
                  ->get();

      return response()->json($rooms);
    }

    public function detilBooking($kode_booking){
      $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, bookings.jml_kamar as jmlKamar, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      ->first();



      return response()->json($bookings);
    }

    public function loadBooking(){
      $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->select(DB::raw("bookings.kode_booking, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin, bookings.tgl_checkout,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status"))
                      ->get();

      return response()->json($bookings);

    }

    public function prosesBooking(Request $request){

      $this->validate($request, [
        'no_rooms' => 'required|array',
        'no_rooms.*' => 'required',
        'kode_booking' => 'required|unique:bookings',
        'tgl_checkin' => 'required|date',
        'tgl_checkout' => 'required|date',
        'total' => 'required|numeric',
        'nama' => 'required|min:2',
        'tipe_identitas' => 'required',
        'no_identitas' => 'required',
        'alamat' => 'required|min:4',
        'no_telepon' => 'required|min:10',
        'email' => 'required',
        'keterangan' => 'sometimes',
        'jml_tamu' => 'required|array',
        'jml_tamu.*' => 'required',
      ]);

      $user = auth('api')->user();

      DB::beginTransaction();
      try {
        $no_rooms = $request->no_rooms;
        $jml_tamu = $request->jml_tamu;

        if (count($no_rooms) > count($jml_tamu))
          $count = count($jml_tamu);
        else $count = count($no_rooms);

        for ($i=0; $i < $count ; $i++) {
          $room = new BookingRoom();
          $room->kode_booking = $request->kode_booking;
          $room->no_room = $no_rooms[$i];
          $room->jml_tamu = $jml_tamu[$i];
          $room->tgl_checkin = $request->tgl_checkin;
          $room->tgl_checkout = $request->tgl_checkout;
          $room->save();
        }

      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $pelanggan = Pelanggan::updateOrCreate(
          ['email' => $request->email, 'no_identitas' => $request->no_identitas],
          [
          'nama' => $request->nama,
          'email' => $request->email,
          'tipe_identitas' => $request->tipe_identitas,
          'no_identitas' => $request->no_identitas,
          'alamat' => $request->alamat,
          'no_telepon' => $request->no_telepon,
          ]);
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        Booking::create([
          'kode_booking' => $request->kode_booking,
          'jml_kamar' => $room->count(),
          'id_pelanggan' => $pelanggan->id,
          'total' => $request->total,
          'keterangan' => $request->keterangan,
          'tgl_checkin' => $request->tgl_checkin,
          'tgl_checkout' => $request->tgl_checkout,
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $temp = BookingTemp::where('id_users', $user->id)->get();
        foreach ($temp as $tmp) {
          $kamar = Kamar::where('no_room', $tmp->no_room)->update([
            'status_temp' => '0',
          ]);
          $tmp->delete();
        }
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }



      DB::commit();

      return response()->json([
        'msg' => 'Berhasil'
      ]);


    }

    public function hapusTemp($id){
      $temp = BookingTemp::findOrFail($id);

      $kamar = Kamar::where('no_room', $temp->no_room)->first();


      DB::beginTransaction();
      try {
        $kamar->status_temp = '0';
        $kamar->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }


      try {
        $temp->delete();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }



      DB::commit();

      return response()->json([
        'Berhasil dihapus!'
      ]);

    }

    public function loadTemp(){
      $user = auth('api')->user();

      $temp = DB::table('booking_temp')
                  ->join('kamar', 'booking_temp.no_room', 'kamar.no_room')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                  ->select(DB::raw("booking_temp.id, kamar.no_room, tipe_kamar.kapasitas, tipe_kamar.harga, tipe_kamar.tipe, booking_temp.jml_tamu"))
                  ->where('booking_temp.id_users', $user->id)
                  ->get();


      $kd_booking = Str::random(10);

      $subtotal = DB::table('booking_temp')
                      ->select(DB::raw("SUM(harga) as subtotal"))
                      ->where('id_users', $user->id)
                      ->get();

      $tgl = DB::table('booking_temp')
                ->select(DB::raw("tgl_checkin, tgl_checkout"))
                ->where('id_users', $user->id)
                ->first();

      $tgl1 = Carbon::parse($tgl->tgl_checkin);
      $tgl2 = Carbon::parse($tgl->tgl_checkout);
      $durasi = $tgl1->diffInDays($tgl2);

      foreach ($subtotal as $sub) {
        $total = $sub->subtotal * $durasi;
      }

      return response()->json([
        'temp' => $temp,
        'kd_booking' => $kd_booking,
        'sub_total' => $subtotal,
        'durasi' => $durasi,
        'total' => $total,
        'tgl_checkin' => $tgl->tgl_checkin,
        'tgl_checkout' => $tgl->tgl_checkout
      ]);

    }

    public function countTemp(){
      $user = auth('api')->user();

      $temp = BookingTemp::where('id_users', $user->id)->count();

      return response()->json($temp);
    }

    public function addRoom(Request $request){
      $user = auth('api')->user();
      DB::beginTransaction();
      try {
        BookingTemp::create([
          'no_room' => $request->no_room,
          'jml_tamu' => $request->tamu_booking,
          'id_users' => $user->id,
          'harga' => $request->harga,
          'tgl_checkin' => $request->tgl_checkin,
          'tgl_checkout' => $request->tgl_checkout,
        ]);

      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      try {
        $kamar = Kamar::where('no_room', $request->no_room)->first();
        $kamar->status_temp = '1';
        $kamar->save();

      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }


      DB::commit();

      return response()->json([
        'msg' => 'Berhasil disimpan'
      ]);

    }

    public function cekKamar(Request $request){

      $this->validate($request,[
        'tgl_checkin' => 'required',
        'tgl_checkout' => 'required',
        'jml_tamu' => 'required',
      ]);

      $tgl_checkin = $request->tgl_checkin;
      $tgl_checkout = $request->tgl_checkout;
      $jmlKamar = $request->jml_tamu;

      $tgl1 = Carbon::parse($tgl_checkin)->format("d M Y");
      $tgl2 = Carbon::parse($tgl_checkout)->format("d M Y");


      $kamar = Kamar::with('booking', 'tipe')->whereHas('booking', function($q) use ($tgl_checkin, $tgl_checkout){
        $q->where(function($q2) use ($tgl_checkin, $tgl_checkout){
          $q2->where('tgl_checkin', '>=', $tgl_checkout)
             ->orWhere('tgl_checkout', '<=', $tgl_checkin);
        });
      })->orWhereDoesntHave('booking')->where('status_temp', '=', '0')->get();

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
