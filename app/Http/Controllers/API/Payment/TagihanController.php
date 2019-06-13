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
                    ->select(DB::raw("tagihan.id, tagihan.kode_booking, pelanggan.nama, pelanggan.no_telepon, pelanggan.alamat, tagihan.total_tagihan,
                    tagihan.terbayarkan, tagihan.hutang"))
                    ->orderBy('tagihan.id', 'desc')
                    ->get();

      return response()->json($tagihan);
    }
}
