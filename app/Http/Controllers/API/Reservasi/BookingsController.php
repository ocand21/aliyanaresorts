<?php

namespace App\Http\Controllers\API\Reservasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Notifications\InvoiceBooking;
use App\Notifications\NewBooking;
use App\Booking;
use App\Kamar;
use App\TipeKamar;
use App\BookingTemp;
use App\BookingRoom;
use App\Pelanggan;
use App\Tagihan;
use App\BookingCanceled;
use App\BookingType;
use App\Charge;
use App\PelangganWIG;
use Mail;
use Calendar;


use Date;

class BookingsController extends Controller
{

    public function updateBooking(Request $request){

      $pelanggan = Pelanggan::findOrFail($request->user_id);

      $request->validate([
        'nama' => 'required',
        'tipe_identitas' => 'required',
        'no_identitas' => 'required|unique:pelanggan,no_identitas,'.$pelanggan->id,
        'no_telepon' => 'required|unique:pelanggan,no_telepon,'.$pelanggan->id,
        'email' => 'sometimes|unique:pelanggan,email,'.$pelanggan->id,
        'alamat' => 'required',
      ]);

      DB::beginTransaction();
      try {
        $pelanggan->update([
          'nama' => $request->nama,
          'tipe_identitas' => $request->tipe_identitas,
          'no_identitas' => $request->no_identitas,
          'no_telepon' => $request->no_telepon,
          'email' => $request->email,
          'alamat' => $request->alamat,
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Data berhasil diubah',
      ]);

    }

    public function pilihNoKamar(Request $request, $no_room)
    {
        $temp = BookingTemp::where('id', $request->id_temp)->first();

        DB::beginTransaction();
        try {
            $temp->update([
              'no_room' => $no_room,
            ]);
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
        }

        try {
            $kamar = Kamar::where('no_room', $no_room)->first();
            $kamar->update([
              'status_temp' => '1',
            ]);
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
        }


        DB::commit();

        return response()->json([
        'msg' => 'Berhasil',
      ]);
    }

    public function cekNoKamar(Request $request, $id_temp)
    {
        $temp = BookingTemp::where('id', $id_temp)->first();

        $tgl_checkin = $temp->tgl_checkin;
        $tgl_checkout = $temp->tgl_checkout;

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
                ->where([['id_tipe', $temp->id_tipe],['status_temp', '0']])
                ->get();

        $count = $kamar->count();

        return response()->json([
          'id_temp' => $temp->id,
          'avKamar' => $kamar,
          'msg' => $count . ' Kamar tersedia pada ' . $tgl1 . ' sd ' . $tgl2 . ' ',
      ]);
    }

    public function filterTglBooking($tgl_awal, $tgl_akhir)
    {
        $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                      ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                      ->leftJoin('users', 'users.id', 'bookings.id_users')
                      ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                      bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan, tagihan.hutang as kekurangan,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                      WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status,
                      bookings.tipe as tipe_booking, bookings.created_at, users.name as diinput_oleh"))
                      ->orderBy('bookings.tgl_checkin', 'asc')
                      ->whereBetween('bookings.tgl_checkin', [$tgl_awal, $tgl_akhir])
                      ->get();

        return response()->json($bookings);
    }

    public function bookingCalendar()
    {
        $calendardata = DB::table('booking_rooms')
                      ->select(DB::raw("id, no_room as title, tgl_checkin as start_date, tgl_checkout as end_date"))
                      ->get();

        return response()->json([
        'calendardata' => $calendardata,
      ]);
    }

    public function reservasiApps()
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
                      ->orderBy('bookings.id', 'asc')
                      ->get();


        return response()->json($bookings);
    }

    public function filterBooking($filter)
    {
        $date = Carbon::now();

        if ($filter == "2") {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                        WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status,
                        bookings.tipe as tipe_booking"))
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->where('bookings.status', '2')
                        ->get();
            return response()->json($bookings);
        } elseif ($filter == "rsv_today") {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                        WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status, bookings.created_at,
                        bookings.tipe as tipe_booking"))
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->whereDate('bookings.created_at', $date)
                        ->get();
            return response()->json($bookings);
        } elseif ($filter == "expected_arrv") {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                        WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status, bookings.created_at,
                        bookings.tipe as tipe_booking"))
                        ->whereDate('bookings.tgl_checkin', $date)
                        ->where('bookings.status', '1')
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->get();
            return response()->json($bookings);
        } elseif ($filter == "expected_dep") {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                        WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status, bookings.created_at,
                        bookings.tipe as tipe_booking"))
                        ->whereDate('bookings.tgl_checkout', $date)
                        ->where('bookings.status', '2')
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->get();
            return response()->json($bookings);
        }
         else {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                        WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status,
                        bookings.tipe as tipe_booking"))
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->get();
            return response()->json($bookings);
        }
    }

    public function loadCanceled()
    {
        $cancel = DB::table('booking_canceled')
                    ->join('pelanggan_wig', 'pelanggan_wig.id', 'booking_canceled.id_pelanggan')
                    ->join('users', 'users.id', 'booking_canceled.id_user')
                    ->select(DB::raw("booking_canceled.id, booking_canceled.kode_booking, pelanggan_wig.nama, users.name as admin, booking_canceled.created_at"))
                    ->orderBy('booking_canceled.id', 'asc')
                    ->get();

        return response()->json($cancel);
    }

    public function cancelBooking(Request $request)
    {
        $this->validate($request, [
        'kode_booking' => 'required',
        'id_pelanggan' => 'required',
        'alasan' => 'required|min:4',
      ]);

        $user = auth('api')->user();

        DB::beginTransaction();

        try {
            $canceled = new BookingCanceled();
            $canceled->kode_booking = $request->kode_booking;
            $canceled->id_pelanggan = $request->id_pelanggan;
            $canceled->alasan = $request->alasan;
            $canceled->id_user = $user->id;
            $canceled->save();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        try {
            $booking = Booking::where('kode_booking', $request->kode_booking)->first();
            $booking->delete();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $bType = BookingType::where('kode_booking', $request->kode_booking)->get();
            foreach ($bType as $type) {
                $type->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        try {
            $booking_rooms = BookingRoom::where('kode_booking', $request->kode_booking)->get();
            foreach ($booking_rooms as $room) {
                $room->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $tagihan = Tagihan::where('kode_booking', $request->kode_booking)->first();
            $tagihan->delete();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $charges = Charge::where('kode_booking', $request->kode_booking)->get();
            foreach ($charges as $charge) {
                $charge->delete();
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        DB::commit();

        return response()->json([
        'msg' => 'Booking dibatalkan'
      ], 200);
    }

    public function detilKamar($kode_booking)
    {
        $rooms = DB::table('booking_types')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                  ->select(DB::raw("tipe_kamar.id as id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas, booking_types.jml_kamar, tipe_kamar.harga"))
                  ->where('booking_types.kode_booking', $kode_booking)
                  ->get();

        return response()->json($rooms);
    }

    public function detilBooking($kode_booking)
    {
        $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->leftJoin('users', 'bookings.id_users', 'users.id')
                      ->leftJoin('metode_pembayaran', 'metode_pembayaran.id', 'tagihan.id_metode')
                      ->select(DB::raw("bookings.kode_booking, pelanggan.id as user_id, pelanggan.tipe_identitas, pelanggan.no_identitas, pelanggan.tipe_identitas, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, tagihan.total_tagihan, tagihan.terbayarkan, tagihan.hutang,
                      users.name as created_by, bookings.created_at, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status,
                      metode_pembayaran.bank"))
                      ->where('bookings.kode_booking', $kode_booking)
                      ->first();



        return response()->json($bookings);
    }

    public function loadBooking()
    {
        $date = Carbon::now()->format('Y-m-d');

        $bookings = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                      ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                      ->leftJoin('users', 'users.id', 'bookings.id_users')
                      ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                      bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan, tagihan.hutang as kekurangan,
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Reserved' WHEN (bookings.status = 2) THEN 'Inhouse'
                      WHEN (bookings.status = 3) THEN 'Completed' ELSE 'Cancel' END) as status,
                      bookings.tipe as tipe_booking, bookings.created_at, users.name as diinput_oleh"))
                      ->whereNotIn('bookings.status', ['3'])
                      ->orderBy('bookings.tgl_checkin', 'asc')
                      ->get();


        return response()->json($bookings);
    }

    public function prosesBooking(Request $request)
    {
        $this->validate($request, [
        // 'no_rooms' => 'required|array',
        // 'no_rooms.*' => 'required',
        'id_tipe' => 'required|array',
        'id_tipe.*' => 'required',
        'no_room' => 'required|array',
        'no_room.*' => 'required',
        'kode_booking' => 'required',
        'tgl_checkin' => 'required|date',
        'tgl_checkout' => 'required|date',
        'total' => 'required|numeric',
        'nama' => 'required|min:2',
        'tipe_identitas' => 'required',
        'no_identitas' => 'required',
        'alamat' => 'required|min:4',
        'no_telepon' => 'required|min:10',
        'email' => 'sometimes',
        'keterangan' => 'sometimes',
        'jml_kamar' => 'required|array',
        'jml_kamar.*' => 'required',
      ]);

        $user = auth('api')->user();

        DB::beginTransaction();

        try {
            $pelanggan = Pelanggan::where('no_identitas', $request->no_identitas)->first();
            if (!$pelanggan) {
                $pelanggan = new Pelanggan();
                $pelanggan->email = $request->email;
                $pelanggan->nama = $request->nama;
                $pelanggan->tipe_identitas = $request->tipe_identitas;
                $pelanggan->no_identitas = $request->no_identitas;
                $pelanggan->alamat = $request->alamat;
                $pelanggan->no_telepon = $request->no_telepon;
                $pelanggan->save();
            } else {
                $pelanggan->update([
                  'nama' => $request->nama,
                  'tipe_identitas' => $request->tipe_identitas,
                  'no_identitas' => $request->no_identitas,
                  'alamat' => $request->alamat,
                  'no_telepon' => $request->no_telepon,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $booking = Booking::create([
              'kode_booking' => $request->kode_booking,
              'id_pelanggan' => $pelanggan->id,
              'total' => $request->total,
              'keterangan' => $request->keterangan,
              'tgl_checkin' => $request->tgl_checkin,
              'tgl_checkout' => $request->tgl_checkout,
              'id_users' => $user->id,
              'tipe' => $request->tipe,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $id_tipe = $request->id_tipe;
            $jml_kamar = $request->jml_kamar;
            if (count($id_tipe) > count($jml_kamar)) {
                $count = count($jml_kamar);
            } else {
                $count = count($id_tipe);
            }

            for ($i=0; $i < $count ; $i++) {
                $tipe = new BookingType();
                $tipe->kode_booking = $request->kode_booking;
                $tipe->id_tipe = $id_tipe[$i];
                $tipe->jml_kamar = $jml_kamar[$i];
                $tipe->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        try {
            $no_room = $request->no_room;
            $id_tipe = $request->id_tipe;
            if (count($id_tipe) > count($no_room)) {
                $count = count($no_room);
            } else {
                $count = count($id_tipe);
            }

            for ($i=0; $i <  $count; $i++) {
                $bRoom = new BookingRoom();
                $bRoom->kode_booking = $booking->kode_booking;
                $bRoom->no_room = $no_room[$i];
                $bRoom->tgl_checkin = $booking->tgl_checkin;
                $bRoom->tgl_checkout = $booking->tgl_checkout;
                $bRoom->nama_penghuni = $pelanggan->nama;
                $bRoom->jml_penghuni = '1';
                $bRoom->save();
            }
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

        try {
            $tagihan = Tagihan::create([
          'kode_booking' => $request->kode_booking,
          'id_pelanggan' => $pelanggan->id,
          'total_tagihan' => $request->total,
          'terbayarkan' => '0',
          'hutang' => $request->total,
          'status' => '0',
        ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }



        // try {
        // $pelanggan->notify(new InvoiceBooking($request->kode_booking));
        // } catch (\Exception $e) {
        //   DB::rollback();
        //   throw $e;
        // }

        DB::commit();

        return response()->json([
        'msg' => 'Berhasil'
      ]);
    }

    public function hapusTemp($id)
    {
        $temp = BookingTemp::findOrFail($id);

        if ($temp->no_room != null) {
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



        } else {
            try {
                $temp->delete();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }


        DB::commit();

        return response()->json([
        'Berhasil dihapus!'
      ]);
    }

    public function loadTemp()
    {
        $user = auth('api')->user();

        $temp = DB::table('booking_temp')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'booking_temp.id_tipe')
                  ->select(DB::raw("booking_temp.id, booking_temp.no_room, booking_temp.id_tipe, tipe_kamar.kapasitas, tipe_kamar.harga, tipe_kamar.tipe, booking_temp.jml_kamar"))
                  ->where('booking_temp.id_users', $user->id)
                  ->get();


        $kd_booking = Str::random(10);

        $subtotal = DB::table('booking_temp')
                      ->select(DB::raw("SUM(harga*jml_kamar) as subtotal"))
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

    public function countTemp()
    {
        $user = auth('api')->user();

        $temp = BookingTemp::where('id_users', $user->id)->count();

        return response()->json($temp);
    }

    public function addRoom(Request $request, $id_tipe)
    {
        $user = auth('api')->user();
        $jml_kamar = $request->jml_kamar;
        // dd($jml_kamar);
        $tipe = TipeKamar::findOrFail($id_tipe);
        DB::beginTransaction();
        try {
            BookingTemp::create([
              'id_tipe' => $id_tipe,
              'jml_kamar' => $request->jml_kamar,
              'id_users' => $user->id,
              'harga' => $tipe->harga,
              'tgl_checkin' => $request->tgl_checkin,
              'tgl_checkout' => $request->tgl_checkout,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }


        DB::commit();

        return response()->json([
          'msg' => 'Berhasil disimpan'
        ]);
    }

    public function cekKamar(Request $request)
    {
        $this->validate($request, [
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
            $kamar = DB::table('kamar')
                    ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                    ->select(DB::raw("kamar.id_tipe, kamar.no_room, tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga, COUNT(kamar.no_room) as jml_kamar, SUM(COUNT(kamar.no_room)) OVER() as total_kamar"))
                    ->whereNotIn('kamar.no_room', function ($q) use ($tgl_checkin, $tgl_checkout) {
                        $q->select('no_room')->from('booking_rooms')
                        ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                        // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                        // ->whereBetween('bookings.tgl_checkin', [$tgl_checkout, $tgl_checkin]);
                        ->where([['bookings.tgl_checkin', '<=', $tgl_checkout],['bookings.tgl_checkout', '>=', $tgl_checkin]]);
                    })
                    ->where([['status_temp', '0']])
                    ->groupBy('kamar.id_tipe')
                    ->get();
        } else {
            $kamar = DB::table('kamar')
                    ->join('tipe_kamar', 'tipe_kamar.id', 'kamar.id_tipe')
                    ->select(DB::raw("kamar.id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga, COUNT(kamar.no_room) as jml_kamar, SUM(COUNT(kamar.no_room)) OVER() as total_kamar"))
                    ->whereNotIn('kamar.no_room', function ($q) use ($tgl_checkin, $tgl_checkout) {
                        $q->select('no_room')->from('booking_rooms')
                        ->join('bookings', 'bookings.kode_booking', 'booking_rooms.kode_booking')
                        // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout])
                        // ->whereBetween('bookings.tgl_checkin', [$tgl_checkin, $tgl_checkout]);
                        ->where([['bookings.tgl_checkin', '<=', $tgl_checkout],['bookings.tgl_checkout', '>=', $tgl_checkin]]);
                    })->where([['tipe_kamar.id', $tipe], ['status_temp', '0']])
                    ->groupBy('kamar.id_tipe')
                    ->get();
        }



        foreach ($kamar as $kmr) {
            $ttlKamar = $kmr->total_kamar;
            $jml = 2 * $kmr->total_kamar;
        }
        // $count = $kamar->count();


        if ($jmlKamar > $jml) {
            return response()->json([
          'kamar' => null,
          'msg' => 'Kamar tidak tersedia. Silakan ganti tanggal atau kurangi jumlah.'
        ]);
        } else {
            return response()->json([
          'kamar' => $kamar,
          'msg' => $ttlKamar . ' Kamar tersedia pada ' . $tgl1 . ' sd ' . $tgl2 . ' untuk ' . $jmlKamar . ' orang tamu',
        ]);
        }
    }
}
