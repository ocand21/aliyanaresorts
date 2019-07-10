<?php

namespace App\Http\Controllers\API\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Charge;
class ChargesController extends Controller
{
    public function delete(Request $request, $id){
      $charge = Charge::findOrFail($id)->delete();

      return response()->json([
        'msg' => 'Berhasil'
      ]);
    }

    public function update(Request $request, $id){
      $request->validate([
        'nama_charge' => 'required',
        'jumlah_persen' => 'required',
        'jumlah_rupiah' => 'required',
        'keterangan' => 'required',
      ]);

      $charge = Charge::findOrFail($id)->update([
        'nama_charge' => $request->nama_charge,
        'jumlah_persen' => $request->jumlah_persen,
        'jumlah_rupiah' => $request->jumlah_rupiah,
        'keterangan' => $request->keterangan,
      ]);

      return response()->json([
        'msg' => 'Berhasil',
      ]);
    }

    public function index(){
      $charge = Charge::orderBy('id', 'asc')->get();

      return response()->json($charge);
    }

    public function store(Request $request){
      $request->validate([
        'nama_charge' => 'required',
        'jumlah_persen' => 'sometimes',
        'jumlah_rupiah' => 'sometimes',
        'keterangan' => 'sometimes',
      ]);

      $charge = Charge::create([
        'nama_charge' => $request->nama_charge,
        'jumlah_persen' => $request->jumlah_persen,
        'jumlah_rupiah' => $request->jumlah_rupiah,
        'keterangan' => $request->keterangan,
      ]);

      return response()->json([
        'msg' => 'Berhasil',
      ]);
    }
}
