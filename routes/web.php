<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\KirController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| ROOT – redirect ke login
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
         ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
         ->name('login.post');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
         ->name('logout');

    // Dashboard – admin + auditor
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | READ ONLY – admin + auditor boleh akses
    |----------------------------------------------------------------------
    */

    // Pegawai – lihat saja
    Route::get('pegawai',           [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('pegawai.show')
         ->where('pegawai', '[0-9]+');

    // Ruangan – lihat saja
    Route::get('ruangan',             [RuanganController::class, 'index'])->name('ruangan.index');
    Route::get('ruangan/{ruangan}',   [RuanganController::class, 'show'])->name('ruangan.show')
         ->where('ruangan', '[0-9]+');

    // Aset – lihat saja
    Route::get('aset',        [AsetController::class, 'index'])->name('aset.index');
    Route::get('aset/{aset}', [AsetController::class, 'show'])->name('aset.show')
         ->where('aset', '[0-9]+');

    // KIR – lihat saja (step 1 & 2 & detail)
    Route::get('kir',                        [KirController::class, 'index'])->name('kir.index');
    Route::post('kir/pilih-ruangan',         [KirController::class, 'pilihRuangan'])->name('kir.pilih-ruangan');
    Route::get('kir/ruangan/{ruangan}',      [KirController::class, 'list'])->name('kir.list');
    Route::get('kir/{kir}',                  [KirController::class, 'show'])->name('kir.show')
         ->where('kir', '[0-9]+');
    Route::get('kir/api/filter-aset',        [KirController::class, 'filterAset'])->name('kir.filter-aset');

    // PIC – lihat history saja
    Route::get('pic/history', [PicController::class, 'history'])->name('pic.history');

    // API internal
    Route::get('api/pegawai/{pegawai}/nip',  [PegawaiController::class, 'getNip'])->name('api.pegawai.nip');
    Route::get('pic/ruangan/{ruangan}',      [PicController::class, 'getPicTerakhir'])->name('pic.get');

    /*
    |----------------------------------------------------------------------
    | ADMIN ONLY – akses penuh CRUD
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        /*
        |----------------------------------------------------------------------
        | Pegawai – CRUD (selain index & show)
        |----------------------------------------------------------------------
        */
        Route::get('pegawai/create',          [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('pegawai',                [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('pegawai/{pegawai}/edit',  [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('pegawai/{pegawai}',       [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('pegawai/{pegawai}',    [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

        /*
        |----------------------------------------------------------------------
        | Ruangan – CRUD (selain index & show)
        |----------------------------------------------------------------------
        */
        Route::get('ruangan/create',              [RuanganController::class, 'create'])->name('ruangan.create');
        Route::post('ruangan',                    [RuanganController::class, 'store'])->name('ruangan.store');
        Route::get('ruangan/{ruangan}/edit',      [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('ruangan/{ruangan}',           [RuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('ruangan/{ruangan}',        [RuanganController::class, 'destroy'])->name('ruangan.destroy');
        Route::get('ruangan/{ruangan}/kelola-aset',  [RuanganController::class, 'kelolaAset'])->name('ruangan.kelola-aset');
        Route::post('ruangan/{ruangan}/kelola-aset', [RuanganController::class, 'simpanAset'])->name('ruangan.simpan-aset');

        /*
        |----------------------------------------------------------------------
        | Aset – CRUD (selain index & show)
        |----------------------------------------------------------------------
        */
        Route::get('aset/create',       [AsetController::class, 'create'])->name('aset.create');
        Route::post('aset',             [AsetController::class, 'store'])->name('aset.store');
        Route::get('aset/{aset}/edit',  [AsetController::class, 'edit'])->name('aset.edit');
        Route::put('aset/{aset}',       [AsetController::class, 'update'])->name('aset.update');
        Route::delete('aset/{aset}',    [AsetController::class, 'destroy'])->name('aset.destroy');

        /*
        |----------------------------------------------------------------------
        | KIR – tambah & hapus
        |----------------------------------------------------------------------
        */
        Route::get('kir/ruangan/{ruangan}/tambah',  [KirController::class, 'create'])->name('kir.create');
        Route::post('kir/ruangan/{ruangan}/simpan', [KirController::class, 'store'])->name('kir.store');
        Route::delete('kir/{kir}',                  [KirController::class, 'destroy'])->name('kir.destroy');

        /*
        |----------------------------------------------------------------------
        | PIC – CRUD
        |----------------------------------------------------------------------
        */
        Route::get('pic',             [PicController::class, 'index'])->name('pic.index');
        Route::get('pic/create',      [PicController::class, 'create'])->name('pic.create');
        Route::post('pic',            [PicController::class, 'store'])->name('pic.store');

        /*
        |----------------------------------------------------------------------
        | Mutasi Aset (uncomment setelah MutasiController dibuat)
        |----------------------------------------------------------------------
        */
        // Route::get('mutasi',              [MutasiController::class, 'index'])->name('mutasi.index');
        // Route::get('mutasi/create',       [MutasiController::class, 'create'])->name('mutasi.create');
        // Route::post('mutasi',             [MutasiController::class, 'store'])->name('mutasi.store');
        // Route::get('mutasi/{mutasi}',     [MutasiController::class, 'show'])->name('mutasi.show');

    });

    /*
    |----------------------------------------------------------------------
    | LAPORAN – admin + auditor
    |----------------------------------------------------------------------
    */
     Route::prefix('laporan')->name('laporan.')->group(function () {
          Route::get('cetak-kir/{kir}',        [LaporanController::class, 'cetakKirForm'])->name('cetak-kir.form');
          Route::post('cetak-kir/{kir}/pdf',   [LaporanController::class, 'cetakKirPdf'])->name('cetak-kir.pdf');
          Route::post('cetak-kir/{kir}/excel', [LaporanController::class, 'cetakKirExcel'])->name('cetak-kir.excel');
     });

});