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
    'admin/metode-pembayaran' => 'API\MasterData\MetodePembayaranController',
  ]);

  Route::get('/admin/booking/calendar', 'API\Reservasi\BookingsController@bookingCalendar');
  
  Route::get('/admin/booking/apps', 'API\Reservasi\BookingsController@reservasiApps');

  Route::delete('/admin/checkin/kamar/{id_room}', 'API\Reservasi\CheckinController@hapusRoom');
  Route::post('/admin/checkin/update/opsional', 'API\Reservasi\CheckinController@updateOpsional');
  Route::get('/admin/checkin/opsional/{id_room}', 'API\Reservasi\CheckinController@opsionalKamar');
  Route::get('/admin/checkin/room/{kode_booking}', 'API\Reservasi\CheckinController@checkinRoom');

  Route::post('/admin/tipe-kamar/edit/{id}', 'API\TipeKamarController@update');
  Route::get('/admin/tipe-kamar/foto/{id_tipe}', 'API\TipeKamarController@loadFoto');
  Route::delete('/admin/tipe-kamar/foto/hapus/{id}', 'API\TipeKamarController@hapusFoto');
  Route::post('/admin/tipe-kamar/foto/upload/{id_tipe}', 'API\TipeKamarController@uploadFoto');

  Route::get('/admin/fasilitas/foto/{id_fasilitas}', 'API\FasilitasController@loadFoto');


  Route::post('/admin/booking/cek-kamar', 'API\Reservasi\BookingsController@cekKamar');
  Route::post('/admin/booking/room/add/{id_tipe}', 'API\Reservasi\BookingsController@addRoom');
  Route::get('/admin/booking/room/temp/count', 'API\Reservasi\BookingsController@countTemp');
  Route::get('/admin/booking/room/temp', 'API\Reservasi\BookingsController@loadTemp');

  Route::delete('/admin/booking/room/temp/hapus/{id}', 'API\Reservasi\BookingsController@hapusTemp');

  Route::post('/admin/booking/proses', 'API\Reservasi\BookingsController@prosesBooking');
  Route::get('/admin/booking', 'API\Reservasi\BookingsController@loadBooking');
  Route::get('/admin/booking/detil/{kode_booking}', 'API\Reservasi\BookingsController@detilBooking');
  Route::get('/admin/booking/detil/kamar/{kode_booking}', 'API\Reservasi\BookingsController@detilKamar');
  Route::post('/admin/booking/cancel', 'API\Reservasi\BookingsController@cancelBooking');
  Route::get('/admin/booking/canceled', 'API\Reservasi\BookingsController@loadCanceled');
  Route::get('/admin/booking/filter/{filter}', 'API\Reservasi\BookingsController@filterBooking');

  Route::get('/admin/checkin/tagihan/{kode_booking}', 'API\Reservasi\CheckinController@loadTagihan');
  Route::get('/admin/checkin/load-charges/{kode_booking}', 'API\Reservasi\CheckinController@loadCharges');
  Route::post('/admin/checkin/charges/{kode_booking}', 'API\Reservasi\CheckinController@applyCharges');
  Route::get('/admin/checkin', 'API\Reservasi\CheckinController@loadData');
  Route::get('/admin/checkin/detil/{kode_booking}', 'API\Reservasi\CheckinController@detilCheckin');
  Route::post('/admin/checkin/proses/{kode_booking}', 'API\Reservasi\CheckinController@prosesCheckin');
  Route::get('/admin/checkin/{tgl_awal}/{tgl_akhir}', 'API\Reservasi\CheckinController@filterTgl');
  Route::post('/admin/checkin/cek-kamar/{id_tipe}', 'API\Reservasi\CheckinController@cekKamar');
  Route::post('/admin/checkin/pilih-kamar/{no_room}', 'API\Reservasi\CheckinController@pilihKamar');





  Route::get('/admin/inhouse', 'API\Reservasi\InhouseController@index');

  Route::get('/admin/payment/tagihan', 'API\Payment\TagihanController@loadTagihan');
  Route::get('/admin/payment/tagihan/{kode_booking}', 'API\Payment\TagihanController@detilTagihan');

  Route::get('/admin/payment/tagihan/cash/{kode_booking}', 'API\Payment\TagihanController@paymentCash');
  Route::get('/admin/payment/tagihan/transfer/{kode_booking}', 'API\Payment\TagihanController@paymentTransfer');
  Route::get('/admin/payment/pembayaran', 'API\Payment\PembayaranController@index');
  Route::get('/admin/payment/pembayaran/riwayat/{kode_booking}', 'API\Payment\PembayaranController@detilPembayaran');
  Route::post('/admin/payment/pembayaran/konfirm/{id}', 'API\Payment\PembayaranController@konfirm');
  Route::post('/admin/payment/cash', 'API\Payment\PembayaranController@cashPayment');
  Route::post('/admin/payment/transfer', 'API\Payment\PembayaranController@transferPayment');
  Route::get('/admin/payment/metode', 'API\MasterData\MetodePembayaranController@loadMetode');



  Route::post('/admin/slideshow', 'API\MasterData\SlideshowController@store');
  Route::get('/admin/slideshow', 'API\MasterData\SlideshowController@index');
  Route::delete('/admin/slideshow/{id}', 'API\MasterData\SlideshowController@destroy');

  Route::get('/admin/menu-resto', 'API\MasterData\MenuRestoController@index');
  Route::post('/admin/menu-resto/tambah', 'API\MasterData\MenuRestoController@store');
  Route::delete('/admin/menu-resto/hapus/{id}', 'API\MasterData\MenuRestoController@destroy');

  Route::post('/admin/charges/{id}', 'API\MasterData\ChargesController@update');
  Route::delete('/admin/charges/{id}', 'API\MasterData\ChargesController@delete');
  Route::post('/admin/charges', 'API\MasterData\ChargesController@store');
  Route::get('/admin/charges', 'API\MasterData\ChargesController@index');

  Route::get('/admin/checkout', 'API\Reservasi\CheckoutController@loadData');
  Route::get('/admin/checkout/{tgl_awal}/{tgl_akhir}', 'API\Reservasi\CheckoutController@filterTgl');
  Route::post('/admin/checkout/proses/{kode_booking}', 'API\Reservasi\CheckoutController@prosesCheckout');


});


Route::group(['prefix' => 'apps'], function(){


  Route::get('/kamar/tipe', 'API\App\AppsController@tipeKamar');
  Route::get('/kamar', 'API\App\AppsController@loadKamar');
  Route::get('/kamar/{id}', 'API\App\AppsController@detailKamar');

  Route::get('/fasilitas', 'API\App\AppsController@loadFasilitas');
  Route::get('/fasilitas/{id}', 'API\App\AppsController@detailFasilitas');


    Route::get('/slideshow', 'API\App\AppsController@slideshow');
    Route::get('/about', 'API\App\AppsController@about');

  Route::post('/booking/cek-kamar', 'API\App\BookingsController@cekKamar');

  Route::get('/menu-resto', 'API\App\AppsController@menuResto');


});
