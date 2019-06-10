<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Departemen;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departemen = DB::table("departemen")
                          ->select(DB::raw("id, kode_departemen, nama_departemen"))
                          ->orderBy('kode_departemen', 'ASC')
                          ->get();

        return response()->json($departemen);
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
          'kode_departemen' => 'required|unique:departemen',
          'nama_departemen' => 'required|min:3|unique:departemen',
        ]);

      DB::beginTransaction();
      try {
        $departemen = new Departemen();
        $departemen->kode_departemen = $request->kode_departemen;
        $departemen->nama_departemen = $request->nama_departemen;
        $departemen->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Departemen berhasil ditambah'
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
        $departemen = Departemen::findOrFail($id);

        $this->validate($request, [
          'kode_departemen' => 'required|unique:departemen,kode_departemen,'.$departemen->id,
          'nama_departemen' => 'required|min:3|unique:departemen,nama_departemen,'.$departemen->id,
        ]);

        DB::beginTransaction();
        try {
          $departemen->kode_departemen = $request->kode_departemen;
          $departemen->nama_departemen = $request->nama_departemen;
          $departemen->update();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Departemen berhasil diubah'
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
        $departemen = Departemen::findOrFail($id);

        DB::beginTransaction();
        try {
          $departemen->delete();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Departemen berhasil dihapus'
        ]);

    }
}
