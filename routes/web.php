<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Obat\ObatController;
use App\Http\Controllers\Jadwal\JadwalController;
use App\Http\Controllers\Layanan\LayananController;
use App\Http\Controllers\Pasien\PasienController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route untuk aplikasi klinik
|
*/

Auth::routes();

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware('checkRole:dokter,admin,pasien');

// Generate PDF pasien
Route::get('/generate-pdf/{pasien}', [PasienController::class, 'generatePDF'])
    ->name('generatePDF')
    ->middleware('checkRole:dokter,admin');

// Login page
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// Dokter CRUD
Route::prefix('dokter')->middleware('checkRole:dokter,admin')->group(function () {
    Route::get('/', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('/create', [DokterController::class, 'create'])->name('dokter.create');
    Route::post('/', [DokterController::class, 'store'])->name('dokter.store');
    Route::get('/{dokter}/edit', [DokterController::class, 'edit'])->name('dokter.edit');
    Route::put('/{dokter}', [DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/{dokter}', [DokterController::class, 'destroy'])->name('dokter.destroy');
});

// Pasien CRUD
Route::resource('pasien', PasienController::class)
    ->middleware('checkRole:dokter,pasien,admin');

// Admin CRUD
Route::resource('admin', AdminController::class)
    ->middleware('checkRole:admin');

// Obat CRUD
Route::resource('obat', ObatController::class)
    ->middleware('checkRole:dokter,admin');

// Jadwal CRUD
Route::resource('jadwal', JadwalController::class)
    ->middleware('checkRole:dokter,admin');
    // Jadwal CRUD
Route::resource('layanan', LayananController::class)
    ->middleware('checkRole:dokter,admin');
