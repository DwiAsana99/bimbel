<?php

Auth::routes();
Route::group(['middleware' => 'auth'], function() {
	Route::get('/', function () { return redirect()->route('home'); });

	Route::prefix('home')->group(function () {
		Route::get('/', 'HomeController@index')->name('home');
	});

	Route::post('/tutupbuku/update', 'TutupBukuController@update')->name('tutupbuku.update');
	Route::get('/tutupbuku/getpostingdata', 'TutupBukuController@getPostingData')->name('tutupbuku.getpostingdata');

	Route::prefix('select2')->group(function () {
		Route::get('/nasabah', 'NasabahController@select2')->name('select2.nasabah');
		Route::get('/deposito/{status}', 'DepositoController@select2')->name('select2.deposito');
		Route::get('/nasabah/pinjaman', 'PinjamanController@select2nasabah')->name('select2.nasabah.pinjaman');
		Route::get('/pinjaman', 'PinjamanController@select2')->name('select2.pinjaman');
		Route::get('/pinjaman/lunas', 'PinjamanController@select2lunas')->name('select2.pinjaman.lunas');
		Route::get('/tabungan', 'TabunganController@select2')->name('select2.tabungan');
		Route::get('/kolektor', 'MasterKolektorController@select2')->name('select2.kolektor');
		
		Route::prefix('akun')->group(function () {
			Route::get('/group', 'AkunController@select2group')->name('select2.akun.group');
			Route::get('/kelompok', 'AkunController@select2kelompok')->name('select2.akun.kelompok');
			Route::get('/akun', 'AkunController@select2akun')->name('select2.akun.akun');
			Route::get('/detil', 'AkunController@select2detil')->name('select2.akun.detil');
		});
	});

	Route::prefix('rbac')->group(function () {
		Route::prefix('user')->group(function () {
			Route::get('/v', 'RBACUserController@validasi')->name('rbac.user.v');
		});
	});

	Route::get('/nasabah/nounik', function () {
		return App\Nasabah::noUnik();
	})->name('nasabah.nounik');
	Route::post('/nasabah/api', 'NasabahController@apiStore')->name('nasabah.api.store');
	Route::get('/nasabah/dt', 'NasabahController@dt')->name('nasabah.dt');
  	Route::put('/nasabah/anggota/{nasabah}', 'NasabahController@anggota')->name('nasabah.anggota');
	Route::get('/nasabah/{nasabah}/isaktif', 'NasabahController@isAktif')->name('nasabah.isaktif');
	Route::resource('nasabah', 'NasabahController', ['except' => [
	    'show', 'destroy'
	]]);

	Route::prefix('simpanan')->group(function () {
		Route::get('/', 'SimpananController@index')->name('simpanan.index');
		Route::get('/dt', 'SimpananController@dt')->name('simpanan.dt');

		Route::prefix('pokok')->group(function () {
			Route::get('/{anggota}', 'SimpananPokokController@index')->name('simpanan.pokok.index');
			Route::get('/{rek}/dt', 'SimpananPokokController@dt')->name('simpanan.pokok.dt');
			Route::post('/{rek}/setor', 'SimpananPokokController@setor')->name('simpanan.pokok.setor');
			Route::post('/{rek}/tarik', 'SimpananPokokController@tarik')->name('simpanan.pokok.tarik');
		});

		Route::prefix('wajib')->group(function () {
			Route::get('/{anggota}', 'SimpananWajibController@index')->name('simpanan.wajib.index');
			Route::get('/{rek}/dt', 'SimpananWajibController@dt')->name('simpanan.wajib.dt');
			Route::post('/{rek}/setor', 'SimpananWajibController@setor')->name('simpanan.wajib.setor');
			Route::post('/{rek}/tarik', 'SimpananWajibController@tarik')->name('simpanan.wajib.tarik');
		});
	});

	Route::prefix('tabungan')->group(function () {
		Route::get('/', 'TabunganController@index')->name('tabungan.index');
		Route::get('/dt', 'TabunganController@dt')->name('tabungan.dt');
		Route::post('/', 'TabunganController@tambah')->name('tabungan.tambah');
		Route::post('/daripinjaman', 'TabunganController@tambahDariPinjaman')->name('tabungan.tambahpinjaman');
		Route::get('/detil/{tabungan?}', 'TabunganController@detil')->name('tabungan.detil');
		Route::get('/detildata/{tabungan?}', 'TabunganController@detildata')->name('tabungan.detildata');
		Route::put('/setor/{tabungan}', 'TabunganController@setor')->name('tabungan.setor');
		Route::put('/tarik/{tabungan}', 'TabunganController@tarik')->name('tabungan.tarik');
	});

	Route::prefix('setorcepat')->group(function () {
		Route::get('/', 'TabunganCepatController@setor')->name('setorcepat.index');
		Route::post('/{tabungan}', 'TabunganCepatController@setorProses')->name('setorcepat.setor');
	});

	Route::prefix('tarikcepat')->group(function () {
		Route::get('/', 'TabunganCepatController@tarik')->name('tarikcepat.index');
		Route::post('/{tabungan}', 'TabunganCepatController@tarikProses')->name('tarikcepat.tarik');
	});

	Route::get('/postingbunga', 'PostingBungaController@index')->name('postingbunga.index');
	Route::get('/postingbunga/dt', 'PostingBungaController@dt')->name('postingbunga.dt');
	Route::post('/postingbunga', 'PostingBungaController@posting')->name('postingbunga.posting');
	Route::get('/postingbunga/{NoRek}', 'PostingBungaController@single')->name('postingbunga.single');

	Route::prefix('simpinjaman')->group(function () {
		Route::get('/', 'SimPinjamanController@index')->name('simpinjaman.index');
		Route::post('/', 'SimPinjamanController@hitung')->name('simpinjaman.hitung');
	});

	Route::prefix('pinjaman')->group(function () {
		Route::get('/', 'PinjamanController@index')->name('pinjaman.index');
		Route::get('/dt', 'PinjamanController@dt')->name('pinjaman.dt');
		Route::get('/create', 'PinjamanController@create')->name('pinjaman.create');
		Route::post('/', 'PinjamanController@tambah')->name('pinjaman.tambah');
		Route::get('/detil/{pinjaman?}', 'PinjamanController@detil')->name('pinjaman.detil');

		Route::get('/bayar/detil/{pinjaman?}', 'PinjamanController@bayardetil')->name('pinjaman.bayar.detil');
		Route::put('/bayar/{pinjaman?}', 'PinjamanController@bayar')->name('pinjaman.bayar');

		Route::get('/lunas/detil/{pinjaman?}', 'PinjamanController@lunasdetil')->name('pinjaman.lunas.detil');
		Route::put('/lunas/{pinjaman?}', 'PinjamanController@lunas')->name('pinjaman.lunas');
	});

	Route::prefix('pinjamanlunas')->group(function () {
		Route::get('/', 'PinjamanLunasController@index')->name('pinjamanlunas.index');
		Route::get('/dt', 'PinjamanLunasController@dt')->name('pinjamanlunas.dt');
		Route::get('/detil/{pinjaman?}', 'PinjamanLunasController@detil')->name('pinjamanlunas.detil');
	});

	Route::get('/kompensasi', 'PinjamanKompenController@index')->name('pinjaman.kompensasi.index');
	Route::get('/kompensasi/dt', 'PinjamanKompenController@dt')->name('pinjaman.kompensasi.dt');
	Route::get('/kompensasi/detil/{nasabah}', 'PinjamanKompenController@detil')->name('pinjaman.kompensasi.detil');
	Route::put('/kompensasi/{pinjaman}', 'PinjamanKompenController@tambah')->name('pinjaman.kompensasi.tambah');

	Route::prefix('deposito')->group(function () {
		Route::get('/', 'DepositoController@index')->name('deposito.index');
		Route::get('/dt', 'DepositoController@dt')->name('deposito.dt');
		Route::get('/detil/{deposito?}', 'DepositoController@detil')->name('deposito.detil');
		Route::post('/', 'DepositoController@tambah')->name('deposito.tambah');
	});

	Route::prefix('depositoberakhir')->group(function () {
		Route::get('/', 'DepositoBerakhirController@index')->name('deposito.berakhir.index');
		Route::get('/dt', 'DepositoBerakhirController@dt')->name('deposito.berakhir.dt');
    	Route::get('/detil/{deposito?}', 'DepositoBerakhirController@detil')->name('deposito.berakhir.detil');
    	Route::put('/tarik/{deposito}', 'DepositoBerakhirController@tarik')->name('deposito.berakhir.tarik');
    	Route::put('/perpanjang/{deposito}', 'DepositoBerakhirController@perpanjang')->name('deposito.berakhir.perpanjang');
	});

	Route::get('/postingdeposito', 'DepositoPostingBungaController@index')->name('deposito.posting.index');
	Route::get('/postingdeposito/dt', 'DepositoPostingBungaController@dt')->name('deposito.posting.dt');
	Route::post('/postingdeposito', 'DepositoPostingBungaController@posting')->name('deposito.posting.posting');

	Route::prefix('m')->group(function () {
		Route::prefix('akun')->group(function () {
			Route::get('/', 'AkunController@index')->name('akun.index');
	
			Route::get('/dt', 'AkunController@dt')->name('akun.dt');
			Route::get('/detil/{akun}', 'AkunController@detil')->name('akun.detil');
		
			Route::get('/last/{kelompok}', function ($kelompok) {
				return App\Akun::noUnik($kelompok);
			})->name('akun.last');
			Route::get('/detil/last/{akun}', function ($akun) {
				return App\Akundetil::noUnik($akun);
			})->name('akun.detil.last');
	
			Route::get('/detil/edit/{id}', function ($id) {
				return App\Akundetil::find($id);
			})->name('akun.detil.edit');
	
			Route::post('/', 'AkunController@storeAkun')->name('akun.store');
			Route::post('/detil', 'AkunController@storeDetil')->name('akun.detil.store');
	
			Route::put('/{akun}', 'AkunController@updateAkun')->name('akun.update');
			Route::put('/detil/{detil}', 'AkunController@updateDetil')->name('akun.detil.update');
		});

		Route::prefix('txtemplate')->group(function () {
			Route::get('/', 'MasterTemplateController@index')->name('m.txtemplate.index');
			Route::get('/dt', 'MasterTemplateController@dt')->name('m.txtemplate.dt');
			Route::post('/', 'MasterTemplateController@store')->name('m.txtemplate.store');
			Route::put('/{template}', 'MasterTemplateController@update')->name('m.txtemplate.update');
		});

		Route::prefix('txtemplate')->group(function () {
			Route::get('/', 'MasterTemplateController@index')->name('m.txtemplate.index');
			Route::get('/dt', 'MasterTemplateController@dt')->name('m.txtemplate.dt');
			Route::post('/', 'MasterTemplateController@store')->name('m.txtemplate.store');
			Route::put('/{template}', 'MasterTemplateController@update')->name('m.txtemplate.update');
		});

		Route::prefix('kolektor')->group(function () {
			Route::get('/', 'MasterKolektorController@index')->name('m.kolektor.index');
			Route::get('/dt', 'MasterKolektorController@dt')->name('m.kolektor.dt');
			Route::get('/create', 'MasterKolektorController@create')->name('m.kolektor.create');
			Route::post('/', 'MasterKolektorController@store')->name('m.kolektor.store');
			Route::get('/edit/{kolektor}', 'MasterKolektorController@edit')->name('m.kolektor.edit');
			Route::put('/{kolektor}', 'MasterKolektorController@update')->name('m.kolektor.update');
		});
	});
  
  	Route::prefix('txlain')->group(function () {
    	Route::get('/', 'TxLainController@index')->name('txlain.index');
    	Route::post('/', 'TxLainController@store')->name('txlain.store');
	});

  	Route::prefix('txtemplate')->group(function () {
		Route::get('/', 'TxTemplateController@index')->name('txtemplate.index');
		Route::get('/dt', 'TxTemplateController@dt')->name('txtemplate.dt');
		Route::get('/detil/{template}', 'TxTemplateController@detil')->name('txtemplate.detil');
		Route::post('/', 'TxTemplateController@store')->name('txtemplate.store');
		Route::put('/{template}', 'TxTemplateController@tambah')->name('txtemplate.tambah');
	});
  
  	Route::prefix('laporan')->group(function () {
		Route::prefix('keu')->group(function () {
			Route::get('/', 'LapKeuController@index')->name('lap.keu.index');
			Route::get('/hari/{tgl}', 'LapKeuController@hari')->name('lap.keu.hari');
			Route::post('/jurnalumum', 'LapKeuController@jurnalUmum')->name('lap.keu.jurnalumum');
			Route::post('/bukubesar', 'LapKeuController@bukuBesar')->name('lap.keu.bukubesar');
			Route::post('/labarugi', 'LapKeuController@labaRugi')->name('lap.keu.labarugi');
			Route::post('/neracapercobaan', 'LapKeuController@neracaPercobaan')->name('lap.keu.neracapercobaan');
			Route::post('/neraca', 'LapKeuController@neraca')->name('lap.keu.neraca');
			Route::post('/neracalajur', 'LapKeuController@neracaLajur')->name('lap.keu.neracalajur');
		});

		Route::prefix('tabungan')->group(function () {
			Route::get('/', 'LapTabunganController@index')->name('lap.tabungan.index');

			Route::post('/semua', 'LapTabunganController@semua')->name('lap.tabungan.semua');
			Route::post('/per', 'LapTabunganController@per')->name('lap.tabungan.per');
			Route::post('/rekap', 'LapTabunganController@rekap')->name('lap.tabungan.rekap');
		});

		Route::prefix('pinjaman')->group(function () {
			Route::get('/', 'LapPinjamanController@index')->name('lap.pinjaman.index');
			Route::post('/semua', 'LapPinjamanController@semua')->name('lap.pinjaman.semua');
			Route::post('/per', 'LapPinjamanController@per')->name('lap.pinjaman.per');
			Route::post('/rekap', 'LapPinjamanController@rekap')->name('lap.pinjaman.rekap');
			Route::post('/realisasi', 'LapPinjamanController@realisasi')->name('lap.pinjaman.realisasi');
			Route::post('/riwayat', 'LapPinjamanController@riwayat')->name('lap.pinjaman.riwayat');
		});

    	Route::prefix('deposito')->group(function () {
      		Route::get('/', 'LapDepositoController@index')->name('lap.deposito.index');
			Route::post('/semua', 'LapDepositoController@semua')->name('lap.deposito.semua');
			Route::post('/per', 'LapDepositoController@per')->name('lap.deposito.per');
			Route::post('/rekap', 'LapDepositoController@rekap')->name('lap.deposito.rekap');
		});
	});

	Route::prefix('setting')->group(function () {
		Route::get('/koperasi', 'SettingKoperasiController@index')->name('setting.koperasi.index');
		Route::put('/koperasi', 'SettingKoperasiController@update')->name('setting.koperasi.update');
		Route::put('/koperasi', 'SettingKoperasiController@update')->name('setting.koperasi.update');

		Route::get('/tabungan', 'SettingTabunganController@index')->name('setting.tabungan.index');
		Route::put('/tabungan', 'SettingTabunganController@update')->name('setting.tabungan.update');

		Route::get('/pinjaman', 'SettingPinjamanController@index')->name('setting.pinjaman.index');
		Route::put('/pinjaman', 'SettingPinjamanController@update')->name('setting.pinjaman.update');

		Route::get('/deposito', 'SettingDepositoController@index')->name('setting.deposito.index');
		Route::put('/deposito', 'SettingDepositoController@update')->name('setting.deposito.update');

		Route::get('/aturtanggal', 'SettingAturTanggalController@index')->name('setting.aturtanggal.index');
		Route::post('/aturtanggal', 'SettingAturTanggalController@update')->name('setting.aturtanggal.update');
	});

	Route::prefix('gantipwd')->group(function () {
    	Route::get('/', 'GantiPasswordController@index')->name('gantipwd.index');
    	Route::post('/', 'GantiPasswordController@ganti')->name('gantipwd.ganti');
	});

	Route::prefix('kwitansi')->group(function () {
    	Route::prefix('pinjaman')->group(function () {
			Route::get('/pembayaran/{NoKwitansi}', 'Kwitansi\KwPinjamanController@pembayaran')->name('kw.pinjaman.pembayaran');
		});
		Route::prefix('kas')->group(function () {
			Route::get('/keluar/{NoKwitansi}', 'Kwitansi\KwKasController@keluar')->name('kw.kas.keluar');
			Route::get('/masuk/{NoKwitansi}', 'Kwitansi\KwKasController@masuk')->name('kw.kas.masuk');
		});
	});
});