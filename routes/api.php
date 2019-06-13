<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function(){

  Route::apiResources([
    'admin/konfigurasi-web' => 'API\KonfigurasiController',
    'admin/tipe-kamar' => 'API\TipeKamarController',
    'admin/kamar' => 'API\KamarController',
    'admin/fasilitas' => 'API\FasilitasController',
    'admin/departemen' => 'API\DepartemenController',
    'admin/staff' => 'API\StaffController',
  ]);

  Route::post('/admin/tipe-kamar/edit/{id}', 'API\TipeKamarController@update');
  Route::get('/admin/tipe-kamar/foto/{id_tipe}', 'API\TipeKamarController@loadFoto');
  Route::delete('/admin/tipe-kamar/foto/hapus/{id}', 'API\TipeKamarController@hapusFoto');
  Route::post('/admin/tipe-kamar/foto/upload/{id_tipe}', 'API\TipeKamarController@uploadFoto');

  Route::get('/admin/fasilitas/foto/{id_fasilitas}', 'API\FasilitasController@loadFoto');


  Route::post('/admin/booking/cek-kamar', 'API\Reservasi\BookingsController@cekKamar');
  Route::post('/admin/booking/room/add', 'API\Reservasi\BookingsController@addRoom');
  Route::get('/admin/booking/room/temp/count', 'API\Reservasi\BookingsController@countTemp');
  Route::get('/admin/booking/room/temp', 'API\Reservasi\BookingsController@loadTemp');

  Route::delete('/admin/booking/room/temp/hapus/{id}', 'API\Reservasi\BookingsController@hapusTemp');

  Route::post('/admin/booking/proses', 'API\Reservasi\BookingsController@prosesBooking');
  Route::get('/admin/booking', 'API\Reservasi\BookingsController@loadBooking');
  Route::get('/admin/booking/detil/{kode_booking}', 'API\Reservasi\BookingsController@detilBooking');
  Route::get('/admin/booking/detil/kamar/{kode_booking}', 'API\Reservasi\BookingsController@detilKamar');

  Route::get('/admin/payment/tagihan', 'API\Payment\TagihanController@loadTagihan');
});


Route::group(['prefix' => 'apps'], function(){

    Route::get('/kamar/tipe', 'API\App\AppsController@tipeKamar');
  Route::get('/kamar', 'API\App\AppsController@loadKamar');
  Route::get('/kamar/{id}', 'API\App\AppsController@detailKamar');

  Route::get('/fasilitas', 'API\App\AppsController@loadFasilitas');
  Route::get('/fasilitas/{id}', 'API\App\AppsController@detailFasilitas');

  Route::post('/booking/cek-kamar', 'API\App\BookingsController@cekKamar');


});
