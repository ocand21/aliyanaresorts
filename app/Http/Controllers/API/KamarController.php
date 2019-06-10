<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Kamar;


class KamarController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kamar = DB::table('kamar')
                     ->join('tipe_kamar', 'tipe_kamar.id', '=', 'kamar.id_tipe')
                     ->select(DB::raw("kamar.id, tipe_kamar.tipe, kamar.no_room, (CASE WHEN (kamar.status = 1) THEN 'Available' ELSE 'Not Available' END) as status"))
                     ->get();

        return response()->json($kamar);
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
          'id_tipe' => 'required',
          'no_room' => 'required|numeric|unique:kamar',
        ]);

        DB::beginTransaction();
        try {
          Kamar::create([
            'id_tipe' => $request->id_tipe,
            'no_room' => $request->no_room,
            'status' => '1',
            'status_temp' => '0',
          ]);
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Kamar berhasil ditambah'
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
      $kamar = Kamar::findOrFail($id);

      $this->validate($request, [
        'id_tipe' => 'required',
        'no_room' => 'required|numeric|unique:kamar,no_room,'.$kamar->id,
      ]);

      DB::beginTransaction();
      try {
        $kamar->update($request->all());
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Kamar berhasil diubah'
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
        $kamar = Kamar::findOrFail($id);

        DB::beginTransaction();
        try {

          $kamar->delete();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Kamar berhasil dihapus'
        ]);
    }
}
