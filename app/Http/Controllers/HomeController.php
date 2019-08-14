<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Admin;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function markAsRead(){
      $user = auth()->user();

      foreach ($user->unreadNotifications as $notification) {
        dd($notification);
        $notification->markAsRead();
      }


    }

    public function index()
    {
        $user = Admin::find(1);
        // dd($user);
        return view('home', compact('user'));
    }
}
