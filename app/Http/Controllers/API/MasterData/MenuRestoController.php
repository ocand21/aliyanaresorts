<?php

namespace App\Http\Controllers\API\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Image;
use App\MenuResto;
class MenuRestoController extends Controller
{

    public function destroy($id){
      $menu = MenuResto::findOrFail($id);

      DB::beginTransaction();
      try {
        $oldFilename = $menu->nama_file;
        $oldPath = public_path('img/menu-resto/' . $oldFilename);
        unlink($oldPath);
        $menu->delete();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Menu berhasil dihapus'
      ]);

    }

    public function store(Request $request){
      $this->validate($request, [
        'foto' => 'required|image|mimes:jpeg,bpm,png',
        'menu' => 'required',
        'nama' => 'required',
        'catatan' => 'sometimes',
        'harga' => 'required',
      ]);

      DB::beginTransaction();
      try {
        $menu = new MenuResto();
        $menu->menu = $request->menu;
        $menu->nama = $request->nama;
        $menu->catatan = $request->catatan;
        $menu->harga = $request->harga;
        $file = $request->foto;

        $filename = time() . '.' . $file->getClientOriginalName();
        $location = public_path('img/menu-resto/' . $filename);
        Image::make($file)->save($location);

        $menu->foto = '/img/menu-resto/' . $filename;
        $menu->nama_file = $filename;
        $menu->save();
      } catch (\Exception $e) {
        DB::rollback();
        throw $e;
      }

      DB::commit();

      return response()->json([
        'msg' => 'Menu berhasil ditambah'
      ]);

    }

    public function index(){
      $menu = MenuResto::orderBy('id', 'asc')->get();

      return response()->json($menu);
    }
}
