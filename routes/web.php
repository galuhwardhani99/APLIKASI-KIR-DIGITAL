<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/', fn() => redirect()->route('login'));

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

    // Dashboard – bisa diakses admin maupun auditor
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | ADMIN ONLY – akses penuh CRUD
    | Tambahkan route modul lain di sini setelah dibuat
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Contoh (uncomment setelah controller dibuat):
        // Route::resource('asets', AsetController::class);
        // Route::resource('ruangans', RuanganController::class);
        // Route::resource('pics', PicController::class);
        // Route::resource('mutasi', MutasiController::class);

    });

    /*
    |----------------------------------------------------------------------
    | ADMIN + AUDITOR – view only
    | Tambahkan route laporan / lihat data di sini
    |----------------------------------------------------------------------
    */
    // Contoh (uncomment setelah controller dibuat):
    // Route::get('laporan/ruangan',  [LaporanController::class, 'ruangan'])->name('laporan.ruangan');
    // Route::get('laporan/kondisi',  [LaporanController::class, 'kondisi'])->name('laporan.kondisi');
    // Route::get('laporan/mutasi',   [LaporanController::class, 'mutasi'])->name('laporan.mutasi');
    // Route::get('laporan/pic',      [LaporanController::class, 'pic'])->name('laporan.pic');

});
