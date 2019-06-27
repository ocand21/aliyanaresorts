<?php

namespace App\Http\Controllers\API\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\MetodePembayaran;

class MetodePembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $metode = MetodePembayaran::orderBy('id', 'asc')->get();

      return response()->json($metode);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'bank' => 'required|',
          'no_rekening' => 'required',
          'atas_nama' => 'required',
        ]);

        MetodePembayaran::create([
          'bank' => $request->bank,
          'no_rekening' => $request->no_rekening,
          'atas_nama' => $request->atas_nama,
        ]);

        return response()->json([
          'msg' => 'Data berhasil ditambah'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $metode = MetodePembayaran::findOrFail($id);

      $this->validate($request, [
        'bank' => 'required|',
        'no_rekening' => 'required|unique:metode_pembayaran,no_rekening,'.$metode->id,
        'atas_nama' => 'required',
      ]);

      $metode->update($request->all());

      return response()->json([
        'msg' => 'Data berhasil diubah'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $metode = MetodePembayaran::findOrFail($id);

        $metode->delete();

        return response()->json([
          'msg' => 'Data berhasil dihapus'
        ]);
    }
}
