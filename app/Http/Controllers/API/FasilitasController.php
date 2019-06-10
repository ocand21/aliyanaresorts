<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\FotoFasilitas;
use App\Fasilitas;

use Image;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fasilitas = DB::table('fasilitas')
                      ->join('foto_fasilitas', 'foto_fasilitas.id_fasilitas', 'fasilitas.id')
                      ->select(DB::raw("fasilitas.id, fasilitas.nama, foto_fasilitas.lokasi"))
                      ->groupBy('fasilitas.id')
                      ->get();

        return response()->json($fasilitas);
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
          'nama' => 'required|min:3',
          'foto' => 'required|array',
          'foto.*' => 'required|image|mimes:jpeg,bpm,png',
        ]);

        DB::beginTransaction();
        try {
          $fasilitas = new Fasilitas();
          $fasilitas->nama = $request->nama;

          $fasilitas->save();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        try {
            $files = $request->file('foto');
            foreach ($files as $file) {
              $filename = time() . '.' . $file->getClientOriginalName();
              $location = public_path('img/fasilitas/' . $filename);
              Image::make($file)->save($location);

              FotoFasilitas::create([
                'id_fasilitas' => $fasilitas->id,
                'lokasi' => '/img/fasilitas/' . $filename,
                'nama_file' => $filename,
              ]);
            }
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Fasilitas berhasil ditambah'
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
        $fasilitas = DB::table('fasilitas')
                        ->join('foto_fasilitas', 'foto_fasilitas.id_fasilitas', 'fasilitas.id')
                        ->select(DB::raw("fasilitas.id, fasilitas.nama"))
                        ->where('fasilitas.id', $id)
                        ->first();

        return response()->json($fasilitas);
    }

    public function loadFoto($id){
      $foto = DB::table('foto_fasilitas')
                  ->select(DB::raw("lokasi"))
                  ->where('id_fasilitas', $id)
                  ->get();

      return response()->json($foto);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        DB::beginTransaction();
        try {
          $photos = FotoFasilitas::where('id_fasilitas', $id)->get();
          foreach ($photos as $photo) {
            $oldFilename = $photo->nama_file;
            $oldPath = public_path('img/fasilitas/' . $oldFilename);
            unlink($oldPath);
            $photo->delete();
          }

        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        try {
          $fasilitas->delete();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Fasilitas berhasil dihapus'
        ]);


    }
}
