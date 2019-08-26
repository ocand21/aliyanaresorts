<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Veritrans_Config;
use Veritrans_Snap;
use Veritrans_Notification;
use Illuminate\Support\Facades\DB;
use App\Pembayaran;
use App\MetodePembayaran;
use App\Model\Payment\TransferPayment;


use Image;
use Carbon\Carbon;
use Session;

class PembayaranController extends Controller
{
    public function getForm($kode_booking)
    {
        $booking = DB::table('bookings')
                      ->join('pelanggan', 'pelanggan.id', 'bookings.id_pelanggan')
                      ->join('tagihan', 'tagihan.kode_booking', 'bookings.kode_booking')
                      ->leftJoin('users', 'bookings.id_users', 'users.id')
                      ->leftJoin('metode_pembayaran', 'metode_pembayaran.id', 'tagihan.id_metode')
                      ->select(DB::raw("bookings.kode_booking, pelanggan.no_identitas, pelanggan.tipe_identitas, pelanggan.nama, pelanggan.email, pelanggan.no_telepon, pelanggan.alamat,
                      bookings.tgl_checkin, bookings.tgl_checkout, bookings.total, tagihan.total_tagihan, tagihan.terbayarkan, tagihan.hutang,
                      users.name as created_by, bookings.id_pelanggan, bookings.created_at, (CASE WHEN (bookings.status = 0) THEN 'Waiting Payment' ELSE 'Payment Accepted' END) as status,
                      metode_pembayaran.bank"))
                      ->where('bookings.kode_booking', $kode_booking)
                      ->first();

        $pelanggan = DB::table('pelanggan')
                       ->select(DB::raw("id, nama, email"))
                       ->where('id', $booking->id_pelanggan)
                       ->first();

         $metode = MetodePembayaran::whereNotIn('bank', ['CASH', 'CREDIT CARD'])->get();

        return view('payment.form', compact('booking', 'pelanggan', 'metode'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'kode_booking' => 'required',
        'image' => 'required|image',
        'tgl_transfer' => 'required',
        'nama_pemilik_rekening' => 'required',
        'no_rekening' => 'required',
        'id_metode' => 'required',
        'jumlah' => 'required',
      ]);


      DB::beginTransaction();
      try {
        // $transfer = TransferPayment::create([
        //   'kode_booking' => $request->kode_booking,
        //   'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
        //   'id_metode' => $request->id_metode,
        //   'no_rekening' => $request->no_rekening,
        //   'tgl_transfer' => $request->tgl_transfer,
        //   'jml_bayar' => $request->jumlah,
        //   'status' => '0',
        //   // 'id_users' => $user->id,
        // ]);

        $transfer = new TransferPayment();
        $transfer->kode_booking = $request->kode_booking;
        $transfer->nama_pemilik_rekening = $request->nama_pemilik_rekening;
        $transfer->id_metode = $request->id_metode;
        $transfer->no_rekening = $request->no_rekening;
        $transfer->tgl_transfer = $request->tgl_transfer;
        $transfer->jml_bayar = $request->jumlah;
        $transfer->status = '0';

        $image = $request->file('image');
        $filename = $transfer->kode_booking;
        $location = public_path('img/bukti-transfer/' . $filename);
        Image::make($image)->save($location);
        $transfer->image = '/img/bukti-transfer/' . $filename;

        $transfer->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }



      DB::commit();


        Session::flash('flash_message', 'Berhasil! Silakan tunggu email pemberitahuan apabila pembayaran telah kami terima');

        return redirect()->back();
    }
}
