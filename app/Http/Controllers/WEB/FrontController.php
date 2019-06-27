<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\KonfigWeb;

class FrontController extends Controller
{
    public function index(){
      return redirect()->route('dashboard');
    }

    public function redirectNotFound(){
      return redirect()->route('index');
    }
}
