<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RuanganController;

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
    | ADMIN ONLY – akses penuh CRUD
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Pegawai
        Route::resource('pegawai', PegawaiController::class);

        // Ruangan
        Route::resource('ruangan', RuanganController::class);

        Route::get('ruangan/{ruangan}/kelola-aset', [RuanganController::class, 'kelolaAset'])
           ->name('ruangan.kelola-aset');

        Route::post('ruangan/{ruangan}/kelola-aset', [RuanganController::class, 'simpanAset'])
           ->name('ruangan.simpan-aset'); 

        // Aset (uncomment setelah AsetController dibuat)
        Route::resource('aset', \App\Http\Controllers\AsetController::class);

        // Mutasi Aset (uncomment setelah MutasiController dibuat)
        // Route::resource('mutasi', MutasiController::class);

        // PIC (uncomment setelah PicController dibuat)
        // Route::resource('pic', PicController::class);

    });

    /*
    |----------------------------------------------------------------------
    | ADMIN + AUDITOR – view only
    |----------------------------------------------------------------------
    */

    // Laporan (uncomment setelah LaporanController dibuat)
    // Route::prefix('laporan')->name('laporan.')->group(function () {
    //     Route::get('ruangan',  [LaporanController::class, 'ruangan'])->name('ruangan');
    //     Route::get('kondisi',  [LaporanController::class, 'kondisi'])->name('kondisi');
    //     Route::get('mutasi',   [LaporanController::class, 'mutasi'])->name('mutasi');
    //     Route::get('pic',      [LaporanController::class, 'pic'])->name('pic');
    // });

    /*
    |----------------------------------------------------------------------
    | API INTERNAL – dipakai JavaScript (dropdown auto-load NIP)
    |----------------------------------------------------------------------
    */
    Route::get('api/pegawai/{pegawai}/nip', [PegawaiController::class, 'getNip'])
         ->name('api.pegawai.nip');

});