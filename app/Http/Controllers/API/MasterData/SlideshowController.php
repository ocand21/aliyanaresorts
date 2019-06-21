<?php

namespace App\Http\Controllers\API\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Slideshow;
use Image;
class SlideshowController extends Controller
{

    public function destroy($id){
      $slide = Slideshow::findOrFail($id);

      DB::beginTransaction();
      try {
        $oldFilename = $slide->nama_file;
        $oldPath = public_path('img/slideshow/' . $oldFilename);
        unlink($oldPath);
        $slide->delete();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Slideshow berhasil dihapus'
      ]);

    }

    public function index(){
      return Slideshow::orderBy('id', 'asc')->get();
    }

    public function store(Request $request){
      $this->validate($request, [
        'judul' => 'required',
        'foto' => 'required|image|mimes:jpeg,bpm,png',
      ]);

      $foto = $request->foto;

      DB::beginTransaction();
      try {

        $filename = time() . '.' . $foto->getClientOriginalName();
        $location = public_path('img/slideshow/' . $filename);
        Image::make($foto)->save($location);

        Slideshow::create([
          'judul' => $request->judul,
          'foto' => '/img/slideshow/' . $filename,
          'nama_file' => $filename,
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Tipe berhasil ditambah'
      ]);

    }
}
