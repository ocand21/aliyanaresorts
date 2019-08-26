<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\User;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff = DB::table("users")
                    ->join('departemen', 'departemen.kode_departemen', 'users.jabatan')
                    ->select(DB::raw("users.id, users.name, users.email, departemen.kode_departemen, departemen.nama_departemen"))
                    ->orderBy('users.id', 'ASC')
                    ->get();

        return response()->json($staff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
          'name' => 'required|min:3',
          'email' => 'required|email|string|unique:users',
          'kode_departemen' => 'required',
          // 'address' => 'required|min:3',
          'password' => 'required|string|min:6|confirmed'
        ]);

        DB::beginTransaction();
        try {
          $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'jabatan' => $request->kode_departemen,
            // 'address' => $request->address,
            'password' => bcrypt($request->password),
            'photo' => public_path('/img/staff/user.png'),
          ]);
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Staff berhasil ditambah'
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
        $staff = User::findOrFail($id);

        $this->validate($request, [
          'kode_departemen' => 'required'
        ]);

        DB::beginTransaction();
        try {
          $staff->kode_departemen = $request->kode_departemen;
          $staff->update();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Staff berhasil diubah'
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
        $staff = User::findOrFail($id);

        DB::beginTransaction();
        try {
          $staff->delete();
        } catch (\Exception $e) {
          DB::rollback();
          throw $e;
        }

        DB::commit();

        return response()->json([
          'msg' => 'Staff berhasil dihapus'
        ]);

    }
}
