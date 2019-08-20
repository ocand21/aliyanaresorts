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

        if ($filter == "3") {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                        WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status,
                        bookings.tipe as tipe_booking"))
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->where('bookings.status', '3')
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
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                        WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status,
                        bookings.tipe as tipe_booking"))
                        ->orderBy('bookings.tgl_checkin', 'asc')
                        ->whereDate('bookings.tgl_checkin', $date)
                        ->get();
            return response()->json($bookings);
        } else {
            $bookings = DB::table('bookings')
                        ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                        ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                        ->join('booking_types', 'booking_types.kode_booking', 'bookings.kode_booking')
                        ->join('tipe_kamar', 'tipe_kamar.id', 'booking_types.id_tipe')
                        ->select(DB::raw("bookings.kode_booking, bookings.id_pelanggan, tipe_kamar.tipe, booking_types.jml_kamar, pelanggan.nama, pelanggan.no_telepon, bookings.tgl_checkin,
                        bookings.tgl_checkout, tagihan.total_tagihan, tagihan.terbayarkan,
                        (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                        WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status,
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
                      ->select(DB::raw("bookings.kode_booking, pelanggan.no_identitas, pelanggan.tipe_identitas, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
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
                      (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' WHEN (bookings.status = 1) THEN 'Payment Accepted' WHEN (bookings.status = 2) THEN 'Checkin'
                      WHEN (bookings.status = 3) THEN 'Inhouse' WHEN (bookings.status = 4) THEN 'Checkout' WHEN (bookings.status = 5) THEN 'Completed' ELSE 'Cancel' END) as status,
                      bookings.tipe as tipe_booking, bookings.created_at, users.name as diinput_oleh"))
                      ->orderBy('bookings.tgl_checkin', 'asc')
                      ->get();

        foreach ($bookings as $booking) {
            if ($booking->tgl_checkin == $date) {
                DB::beginTransaction();
                try {
                    DB::table('bookings')->where([['tgl_checkin', $date],['status', '1']])->update([
              'status' => '2',
            ]);
                } catch (\Exception $e) {
                    throw $e;
                    DB::rollback();
                }

                DB::commit();
            } elseif ($booking->tgl_checkout == $date) {
                DB::beginTransaction();
                try {
                    DB::table('bookings')->where([['tgl_checkout', $date],['status', '3']])->update([
              'status' => '4',
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

    public function prosesBooking(Request $request)
    {
        $this->validate($request, [
        // 'no_rooms' => 'required|array',
        // 'no_rooms.*' => 'required',
        'id_tipe' => 'required|array',
        'id_tipe.*' => 'required',
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
        // try {
        //   $no_rooms = $request->no_rooms;
        //   $jml_tamu = $request->jml_tamu;
        //
        //   if (count($no_rooms) > count($jml_tamu))
        //     $count = count($jml_tamu);
        //   else $count = count($no_rooms);
        //
        //   for ($i=0; $i < $count ; $i++) {
        //     $room = new BookingRoom();
        //     $room->kode_booking = $request->kode_booking;
        //     $room->no_room = $no_rooms[$i];
        //     $room->jml_tamu = $jml_tamu[$i];
        //     $room->tgl_checkin = $request->tgl_checkin;
        //     $room->tgl_checkout = $request->tgl_checkout;
        //     $room->save();
        //   }
        //
        // } catch (\Exception $e) {
        //   DB::rollback();
        //   throw $e;
        // }

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

            auth()->user()->notify(new NewBooking($booking));
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
            $temp = BookingTemp::where('id_users', $user->id)->get();
            foreach ($temp as $tmp) {
                // $kamar = Kamar::where('no_room', $tmp->no_room)->update([
                //   'status_temp' => '0',
                // ]);
                //
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

        // $kamar = Kamar::where('no_room', $temp->no_room)->first();


        // DB::beginTransaction();
        // try {
        //   $kamar->status_temp = '0';
        //   $kamar->save();
        // } catch (\Exception $e) {
        //   DB::rollback();
        //   throw $e;
        // }


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

    public function loadTemp()
    {
        $user = auth('api')->user();

        $temp = DB::table('booking_temp')
                  ->join('tipe_kamar', 'tipe_kamar.id', 'booking_temp.id_tipe')
                  ->select(DB::raw("booking_temp.id, booking_temp.id_tipe, tipe_kamar.kapasitas, tipe_kamar.harga, tipe_kamar.tipe, booking_temp.jml_kamar"))
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
                    ->select(DB::raw("kamar.id_tipe, tipe_kamar.tipe, tipe_kamar.kapasitas, tipe_kamar.harga, COUNT(kamar.no_room) as jml_kamar, SUM(COUNT(kamar.no_room)) OVER() as total_kamar"))
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
