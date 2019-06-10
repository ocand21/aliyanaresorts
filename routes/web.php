<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WEB\FrontController@index')->name('index');


Route::group(['prefix' => 'admin'], function (){
  Auth::routes();

  Route::get('/dashboard', 'HomeController@index')->name('dashboard');
});

Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?');
