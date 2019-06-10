<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\TipeKamar;
use App\FotoKamar;
use Image;

class TipeKamarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TipeKamar::all();
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
          'foto' => 'required|array',
          'foto.*' => 'required|image|mimes:jpeg,bpm,png',
          'tipe' => 'required|min:3|unique:tipe_kamar',
          'fitur' => 'required|min:3',
          'deskripsi' => 'required|min:3',
          'kapasitas' => 'required|numeric|max:2',
          'harga' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
          $tipe = new TipeKamar();
          $tipe->tipe = $request->tipe;
          $tipe->fitur = $request->fitur;
          $tipe->deskripsi = $request->deskripsi;
          $tipe->kapasitas = $request->kapasitas;
          $tipe->harga = $request->harga;

          $tipe->save();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        try {
          $files = $request->file('foto');
          foreach ($files as $file) {
            $filename = time() . '.' . $file->getClientOriginalName();
            $location = public_path('img/tipe-kamar/' . $filename);
            Image::make($file)->save($location);

            FotoKamar::create([
              'id_tipe' => $tipe->id,
              'lokasi' => '/img/tipe-kamar/' . $filename,
              'nama_file' => $filename,
            ]);
          }
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Tipe berhasil ditambah'
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
        $tipe = TipeKamar::findOrFail($id);

        return response()->json($tipe);
    }

    public function loadFoto($id){
      $foto = FotoKamar::where('id_tipe', $id)->get();

      return response()->json($foto);
    }

    public function hapusFoto($id){
      $foto = FotoKamar::findOrFail($id);
      $oldFilename = $foto->nama_file;
      $oldPath = public_path('img/tipe-kamar/' . $oldFilename);
      unlink($oldPath);

      DB::beginTransaction();
      try {
        $foto->delete();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Foto berhasil dihapus'
      ]);


    }

    public function uploadFoto(Request $request, $id){
      $this->validate($request, [
        'foto' => 'required|array',
        'foto.*' => 'required|image|mimes:jpeg,bpm,png',
      ]);

      $files = $request->file('foto');
      DB::beginTransaction();
      try {
        foreach ($files as $file) {
          $filename = time() . '.' . $file->getClientOriginalName();
          $location = public_path('img/tipe-kamar/' . $filename);
          Image::make($file)->save($location);

          FotoKamar::create([
            'id_tipe' => $id,
            'lokasi' => '/img/tipe-kamar/' . $filename,
            'nama_file' => $filename,
          ]);
        }
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

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
        $tipe = TipeKamar::findOrFail($id);
        $this->validate($request, [
          'tipe' => 'required|min:3',
          'fitur' => 'required'
        ]);
        DB::beginTransaction();
        try {
          $tipe->update($request->all());
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();


        return response()->json([
          'msg' => 'Tipe berhasil diubah'
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
        $tipe = TipeKamar::findOrFail($id);

        $photos = FotoKamar::where('id_tipe', $id)->get();

        DB::beginTransaction();
        try {
          foreach ($photos as $photo) {
            $oldFilename = $photo->nama_file;
            $oldPath = public_path('img/tipe-kamar/' . $oldFilename);
            unlink($oldPath);
            $photo->delete();
          }
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        try {
          $tipe->delete();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }


        DB::commit();

        return response()->json([
          'msg' => 'Tipe berhasil dihapus'
        ]);
    }
}
