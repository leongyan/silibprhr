<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::post('backup', 'HomeController@backup');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
		Route::get('icons', ['as' => 'pages.icons', 'uses' => 'PageController@icons']);
		Route::get('maps', ['as' => 'pages.maps', 'uses' => 'PageController@maps']);
		Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'PageController@notifications']);
		Route::get('rtl', ['as' => 'pages.rtl', 'uses' => 'PageController@rtl']);
		Route::get('tables', ['as' => 'pages.tables', 'uses' => 'PageController@tables']);
		Route::get('typography', ['as' => 'pages.typography', 'uses' => 'PageController@typography']);
		Route::get('upgrade', ['as' => 'pages.upgrade', 'uses' => 'PageController@upgrade']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix'=>'sgn','as'=>'sgn.'], function(){
	    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
	    Route::get('karyawan', ['as' => 'karyawan', 'uses' => 'KryController@index']);
	    Route::get('karyawan/{id}', 'KryController@detail');
	    Route::post('karyawan', 'KryController@index2');
	    Route::post('karyawan/tambah', 'KryController@tambah');
	    Route::post('karyawan/edit', 'KryController@edit');
	    // Route::post('karyawan/hapus', 'KryController@hapus');

	    Route::get('pk', ['as' => 'pk', 'uses' => 'PkController@index']);
	    Route::get('pk/{id}', 'PkController@detail');

	    Route::get('kehadiran', ['as' => 'kehadiran', 'uses' => 'KehadiranController@index']);
	    Route::get('kehadiran/{year}/{month}', 'KehadiranController@index');
	    Route::post('kehadiran/edit', 'KehadiranController@edit');
	    Route::post('kehadiran/tambah', 'KehadiranController@tambah');
	    Route::post('kehadiran/getcsv', 'KehadiranController@exportCSV');
	    Route::post('kehadiran/import', 'KehadiranController@import');

	    Route::get('cuti', ['as' => 'cuti', 'uses' => 'CutiController@index']);
	    Route::get('cuti/{year}', 'CutiController@index');
	    Route::post('cuti/tambah', 'CutiController@tambah');
	    Route::post('cuti/edit', 'CutiController@edit');
	    Route::post('cuti/hapus', 'CutiController@hapus');

	    Route::get('gajian', ['as' => 'gajian', 'uses' => 'PayController@index']);
	    Route::get('gajian/{year}/{month}', 'PayController@index');
	    Route::post('gajian/hitung/gaji', 'PayController@hitungGaji');
	    Route::post('gajian/hitung/bpjs', 'PayController@hitungBPJS');
	    Route::post('gajian/simpan/pajak', 'PayController@simpanPajak');
	    Route::post('gajian/hitung/thr', 'PayController@hitungTHR');
	    Route::post('gajian/edit/gaji', 'PayController@editGaji');
	    Route::post('gajian/edit/bpjs', 'PayController@editBPJS');
	    Route::post('gajian/edit/pajak', 'PayController@editPajak');
	    Route::post('gajian/print', 'PayController@printSlip');
	    Route::post('gajian/thr/print', 'PayController@printThr');
	    Route::post('gajian/pajak/csv', 'PayController@getPajakCsv');
	    Route::post('gajian/pajak/csvfinal', 'PayController@getPajakCsvFinal');
	    Route::post('gajian/rekap/csv', 'PayController@getRekapCsv');

	    Route::get('hrlibur', ['as' => 'hrlibur', 'uses' => 'HariLiburController@index']);
	    Route::get('hrlibur/{year}', 'HariLiburController@index');
	    Route::post('hrlibur/edit/fixed', 'HariLiburController@editFixed');
	    Route::post('hrlibur/edit/var', 'HariLiburController@editVar');
	    Route::post('hrlibur/tambah', 'HariLiburController@tambah');
	    Route::post('hrlibur/hapus', 'HariLiburController@hapus');

	    Route::get('report', ['as' => 'report', 'uses' => 'ReportController@index']);
	    Route::get('report/rekapkenaikangajiall', 'ReportController@rekapKenaikanGajiAll');
	    Route::post('report/rekapkenaikangajiall/print', 'ReportController@rekapKenaikanGajiAllPrint');
	    Route::get('report/rekapkenaikangaji', 'ReportController@rekapKenaikanGaji');
	    Route::post('report/rekapkenaikangaji/print', 'ReportController@rekapKenaikanGajiPrint');
	});

	Route::group(['prefix'=>'scb','as'=>'scb.'], function(){
	    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
	    Route::get('karyawan', ['as' => 'karyawan', 'uses' => 'KryController@index']);
	    Route::get('karyawan/{id}', 'KryController@detail');
	    Route::post('karyawan', 'KryController@index2');
	    Route::post('karyawan/tambah', 'KryController@tambah');
	    Route::post('karyawan/edit', 'KryController@edit');
	    // Route::post('karyawan/hapus', 'KryController@hapus');

	    Route::post('pk', ['as' => 'pk', 'uses' => 'KryController@index']);

	    Route::get('kehadiran', ['as' => 'kehadiran', 'uses' => 'KehadiranController@index']);
	    Route::get('kehadiran/{year}/{month}', 'KehadiranController@index');
	    Route::post('kehadiran/edit', 'KehadiranController@edit');
	    Route::post('kehadiran/tambah', 'KehadiranController@tambah');
	    Route::post('kehadiran/getcsv', 'KehadiranController@exportCSV');
	    Route::post('kehadiran/import', 'KehadiranController@import');

	    Route::get('cuti', ['as' => 'cuti', 'uses' => 'CutiController@index']);
	    Route::get('cuti/{year}', 'CutiController@index');
	    Route::post('cuti/tambah', 'CutiController@tambah');
	    Route::post('cuti/edit', 'CutiController@edit');
	    Route::post('cuti/hapus', 'CutiController@hapus');


	    Route::get('gajian', ['as' => 'gajian', 'uses' => 'PayController@index']);
	    Route::get('gajian/{year}/{month}', 'PayController@index');
	    Route::post('gajian/hitung/gaji', 'PayController@hitungGaji');
	    Route::post('gajian/hitung/bpjs', 'PayController@hitungBPJS');
	    Route::post('gajian/simpan/pajak', 'PayController@simpanPajak');
	    Route::post('gajian/hitung/thr', 'PayController@hitungTHR');
	    Route::post('gajian/edit/gaji', 'PayController@editGaji');
	    Route::post('gajian/edit/bpjs', 'PayController@editBPJS');
	    Route::post('gajian/edit/pajak', 'PayController@editPajak');
	    Route::post('gajian/print', 'PayController@printSlip');
	    Route::post('gajian/thr/print', 'PayController@printThr');
	    Route::post('gajian/pajak/csv', 'PayController@getPajakCsv');
	    Route::post('gajian/pajak/csvfinal', 'PayController@getPajakCsvFinal');
	    Route::post('gajian/rekap/csv', 'PayController@getRekapCsv');

	    Route::get('hrlibur', ['as' => 'hrlibur', 'uses' => 'HariLiburController@index']);
	    Route::get('hrlibur/{year}', 'HariLiburController@index');
	    Route::post('hrlibur/edit/fixed', 'HariLiburController@editFixed');
	    Route::post('hrlibur/edit/var', 'HariLiburController@editVar');
	    Route::post('hrlibur/tambah', 'HariLiburController@tambah');
	    Route::post('hrlibur/hapus', 'HariLiburController@hapus');

	    Route::get('report', ['as' => 'report', 'uses' => 'ReportController@index']);
	    Route::get('report/rekapkenaikangajiall', 'ReportController@rekapKenaikanGajiAll');
	    Route::post('report/rekapkenaikangajiall/print', 'ReportController@rekapKenaikanGajiAllPrint');
	    Route::get('report/rekapkenaikangaji', 'ReportController@rekapKenaikanGaji');
	    Route::post('report/rekapkenaikangaji/print', 'ReportController@rekapKenaikanGajiPrint');
	});
});
