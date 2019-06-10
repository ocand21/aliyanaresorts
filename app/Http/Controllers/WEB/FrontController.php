<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\KonfigWeb;

class FrontController extends Controller
{
    public function index(){
      $konfig = KonfigWeb::first();
      return view('public.home', compact('konfig'));
    }

    public function redirectNotFound(){
      return redirect()->route('index');
    }
}
