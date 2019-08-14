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

Route::get('/notif-dibaca', 'HomeController@markAsRead');

Route::group(['prefix' => 'admin'], function (){
  Auth::routes();

  Route::get('/dashboard', 'HomeController@index')->name('dashboard');
});
Route::view('/template-email', 'mail.invoice');
Route::get('/booking/invoice/{kode_booking}', 'PDF\PDFController@invoice')->name('invoice');

Route::get('/booking/invoice/payment/{kode_booking}', 'WEB\PembayaranController@getForm')->name('payment.form');
Route::post('/booking/invoice/payment', 'WEB\PembayaranController@store')->name('payment.store');

Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?');
