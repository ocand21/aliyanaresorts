<?php

namespace App\Http\Controllers\API\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Tagihan;

class TagihanController extends Controller
{
    public function loadTagihan(){
      $tagihan = DB::table('tagihan')
                    ->join('pelanggan', 'pelanggan.id', 'tagihan.id_pelanggan')
                    ->leftJoin('users', 'users.id', 'tagihan.id_user')
                    ->select(DB::raw("tagihan.id, tagihan.kode_booking, pelanggan.nama, pelanggan.no_telepon, pelanggan.alamat, tagihan.total_tagihan,
                    tagihan.terbayarkan, tagihan.hutang, users.name as diterima, (CASE WHEN (tagihan.status = 0) THEN 'Waiting Payment' WHEN (tagihan.status = 1) THEN 'Full Payment'
                    WHEN (tagihan.status = 2) THEN 'Half Payment' WHEN (tagihan.status = 3) THEN 'Completed' END) as status"))
                    ->whereNotIn('tagihan.status', ['3'])
                    ->orderBy('tagihan.id', 'desc')
                    ->get();

      return response()->json($tagihan);
    }
}
