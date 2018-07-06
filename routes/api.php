<?php

// use Illuminate\Http\Request;

// header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::prefix('v1')->group(function () {
  Route::post('kolektor/login', 'AuthController@login');
  Route::post('nasabah/login', 'Nasabah\AuthController@login');
  
  Route::group(['middleware' => 'auth:api'], function() {
    
    Route::group(['prefix' => 'kolektor', 'middleware' => 'can:Api-Kolektor'], function () {
      Route::get('/user/auth', 'UserController@auth');
      Route::get('logout', 'AuthController@logout');


      Route::prefix('tabungan')->group(function () {
        Route::get('/{norek}', 'TabunganController@show');
        Route::post('/tarik/{norek}', 'TabunganController@tarik');
        Route::post('/setor/{norek}', 'TabunganController@setor');
        Route::get('/mutasi/{norek}/{tglawal}/{tglakhir}', 'TabunganController@mutasi');
      });

      Route::prefix('pinjaman')->group(function () {
        Route::get('/{nonasabah}', 'PinjamanController@show');
        Route::get('/bayar/{nonasabah}', 'PinjamanController@tagihan');
        Route::post('/bayar/{nonasabah}', 'PinjamanController@bayar');
      });
    });

    Route::group(['prefix' => 'nasabah', 'middleware' => 'can:Api-Nasabah'], function () {
      Route::get('logout', 'Nasabah\AuthController@logout');

      Route::prefix('tabungan')->group(function () {
        Route::get('/ceksaldo', 'Nasabah\TabunganController@cekSaldo');
        Route::get('/mutasi/{tglawal}/{tglakhir}', 'Nasabah\TabunganController@mutasi');
      });

      Route::prefix('pinjaman')->group(function () {
        Route::get('/kartu', 'Nasabah\PinjamanController@kartu');
      });

      Route::prefix('pengajuan')->group(function () {
        Route::get('/baru', 'Nasabah\PengajuanController@baru');
        Route::post('/baru', 'Nasabah\PengajuanController@baru');
        Route::post('/baru/edit', 'Nasabah\PengajuanController@baru');
        Route::post('/baru/update', 'Nasabah\PengajuanController@baru');

        Route::get('/kompen', 'Nasabah\PengajuanController@kompen');
        Route::post('/kompen', 'Nasabah\PengajuanController@kompen');
        Route::post('/kompen/edit', 'Nasabah\PengajuanController@kompen');
        Route::post('/kompen/update', 'Nasabah\PengajuanController@kompen');
      });
      
    });
  });
});