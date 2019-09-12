<?php

namespace App\Http\Controllers\API\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Tagihan;


use App\Model\Payment\CashPayment;
use App\Model\Payment\TransferPayment;

class TagihanController extends Controller
{

    public function paymentCredit($kode_booking){
      $credit = DB::table('credit_payments')
                    ->join('users', 'users.id', 'credit_payments.id_user')
                    ->join('card_masters', 'card_masters.id', 'credit_payments.card_id')
                    ->select(DB::raw("card_masters.nama_kartu, credit_payments.card_no, credit_payments.card_name, credit_payments.expired_date, credit_payments.jml_bayar, users.name"))
                    ->where('credit_payments.kode_booking', $kode_booking)
                    ->get();

      return response()->json($credit);
    }

    public function paymentTransfer($kode_booking){
      $transfer = DB::table('transfer_payments')
                      ->join('users', 'users.id', 'transfer_payments.id_users')
                      ->join('metode_pembayaran', 'metode_pembayaran.id', 'transfer_payments.id_metode')
                      ->select(DB::raw("metode_pembayaran.bank, transfer_payments.nama_pemilik_rekening, transfer_payments.no_rekening, transfer_payments.jml_bayar, transfer_payments.tgl_transfer, users.name"))
                      ->where('transfer_payments.kode_booking', $kode_booking)
                      ->get();

      return response()->json($transfer);
    }

    public function paymentCash($kode_booking){
      $cash = DB::table('cash_payments')
                  ->join('users', 'users.id', 'cash_payments.id_users')
                  ->select(DB::raw("cash_payments.created_at, cash_payments.jml_bayar, users.name"))
                  ->where('cash_payments.kode_booking', $kode_booking)
                  ->get();

      return response()->json($cash);
    }

    public function detilTagihan($kode_booking){
      $tagihan = DB::table('tagihan')
                    ->join('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
                    ->select(DB::raw("tagihan.id, tagihan.kode_booking, pelanggan.nama, pelanggan.no_telepon, pelanggan.alamat, pelanggan.email, tagihan.total_tagihan,
                    tagihan.terbayarkan, tagihan.hutang, (CASE WHEN (tagihan.status = 0) THEN 'Waiting Payment' WHEN (tagihan.status = 1) THEN 'Full Payment'
                    WHEN (tagihan.status = 2) THEN 'Half Payment' WHEN (tagihan.status = 3) THEN 'Completed' END) as status"))
                    ->where('tagihan.kode_booking', $kode_booking)
                    ->first();

      return response()->json($tagihan);
    }

    public function loadTagihan(){
      $tagihan = DB::table('tagihan')
                    ->join('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
                    ->select(DB::raw("tagihan.id, tagihan.kode_booking, pelanggan.nama, pelanggan.no_telepon, pelanggan.alamat, tagihan.total_tagihan,
                    tagihan.terbayarkan, tagihan.hutang, (CASE WHEN (tagihan.status = 0) THEN 'Waiting Payment' WHEN (tagihan.status = 1) THEN 'Full Payment'
                    WHEN (tagihan.status = 2) THEN 'Half Payment' WHEN (tagihan.status = 3) THEN 'Completed' END) as status"))
                    ->orderBy('tagihan.id', 'desc')
                    ->get();

      return response()->json($tagihan);
    }
}
