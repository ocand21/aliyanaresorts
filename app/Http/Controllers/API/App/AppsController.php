<?php

namespace App\Http\Controllers\API\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\TipeKamar;
use App\FotoKamar;

class AppsController extends Controller
{

    public function tipeKamar(){
      $tipe = DB::table('tipe_kamar')
                  ->select(DB::raw("id, tipe"))
                  ->orderBy('id', 'asc')
                  ->get();

      return response()->json([
        'tipe_kamar' => $tipe
      ]);
    }

    public function loadKamar(){
      $kamar = DB::table('tipe_kamar')
                    ->join('foto_kamar', 'foto_kamar.id_tipe', '=', 'tipe_kamar.id')
                    ->select(DB::raw("tipe_kamar.id, tipe_kamar.tipe, tipe_kamar.harga, tipe_kamar.kapasitas, foto_kamar.lokasi"))
                    ->groupBy('tipe_kamar.id')
                    ->orderBy('tipe_kamar.id', 'ASC')
                    ->get();

      return response()->json([
        'kamar' => $kamar
      ]);
    }

    public function detailKamar($id){
      $kamar = DB::table('tipe_kamar')
                    ->join('foto_kamar', 'foto_kamar.id_tipe', '=', 'tipe_kamar.id')
                    ->select(DB::raw("tipe_kamar.id, foto_kamar.lokasi as foto, tipe_kamar.tipe as nama, tipe_kamar.harga,
                    tipe_kamar.fitur as fasilitas, tipe_kamar.deskripsi"))
                    ->where('tipe_kamar.id', '=', $id)
                    ->groupBy('tipe_kamar.id')
                    ->get();

      return response()->json([
        'kamar' => $kamar
      ]);
    }

    public function loadFasilitas(){
      $fasilitas = DB::table('fasilitas')
                    ->join('foto_fasilitas', 'foto_fasilitas.id_fasilitas', 'fasilitas.id')
                    ->select(DB::raw("fasilitas.id, fasilitas.nama, foto_fasilitas.lokasi"))
                    ->groupBy('fasilitas.id')
                    ->get();

                    return response()->json([
                      'fasilitas' => $fasilitas
                    ]);
    }

    public function detailFasilitas($id){
      $fasilitas = DB::table('fasilitas')
                      ->join('foto_fasilitas', 'foto_fasilitas.id_fasilitas', 'fasilitas.id')
                      ->select(DB::raw("fasilitas.id, fasilitas.nama, foto_fasilitas.lokasi"))
                      ->where('fasilitas.id', $id)
                      ->groupBy('fasilitas.id')
                      ->get();

      return response()->json([
        'fasilitas' => $fasilitas
      ]);
    }
}
