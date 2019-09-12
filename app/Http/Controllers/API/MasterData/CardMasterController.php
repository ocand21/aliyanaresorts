<?php

namespace App\Http\Controllers\API\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\CardMaster;

class CardMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $card = CardMaster::orderBy('id', 'asc')->get();

      return response()->json($card);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'nama_kartu' => 'required',
          'deskripsi' => 'sometimes',
        ]);

        DB::beginTransaction();
        try {
          $card = new CardMaster();
          $card->nama_kartu = $request->nama_kartu;
          $card->deskripsi = $request->deskripsi;

          $card->save();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Berhasil disimpan',
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
        $card = CardMaster::findOrFail($id);

        $request->validate([
          'nama_kartu' => 'required',
          'deskripsi' => 'sometimes',
        ]);

        DB::beginTransaction();
        try {

          $card->update([
            'nama_kartu' => $request->nama_kartu,
            'deskripsi' => $request->deskripsi,
          ]);

        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Berhasil diupdate'
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
        $card = CardMaster::findOrFail($id)->delete();

        return response()->json([
          'msg' => 'Berhasil dihapus',
        ]);
    }
}
