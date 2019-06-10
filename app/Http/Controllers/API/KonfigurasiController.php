<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KonfigWeb;

class KonfigurasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return KonfigWeb::first();
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
          'hotel_name' => 'required|max:255|min:4',
          'alamat' => 'required|max:255|min:4',
          'web' => 'required|max:255|min:4',
          'mail1' => 'required|max:255|min:4',
          'mail2' => 'required|max:255|min:4',
          'instagram' => 'max:255|min:4',
          'phone' => 'required|max:14',
          'facebok' => 'max:255|min:4',
          'fax' => 'required',
          'whatsapp' => 'required',
          'visi' => 'required|min:4',
          'misi' => 'required|min:4',
          'tentang' => 'required|min:4',
        ]);

        return KonfigWeb::create($request->all());
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
        //
    }
}
