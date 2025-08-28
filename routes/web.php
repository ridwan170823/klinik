<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PasienController as AdminPasienController;
use App\Http\Controllers\Dokter\DokterController;
use App\Http\Controllers\Obat\ObatController;
use App\Http\Controllers\Jadwal\JadwalController;
use App\Http\Controllers\Layanan\LayananController;
use App\Http\Controllers\Pasien\PasienController;
use App\Http\Controllers\Antrian\AntrianController;
use App\Http\Controllers\DokterLayanan\DokterLayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentsController;
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

// // Pasien CRUD
// Route::resource('pasien', PasienController::class)
//     ->middleware('checkRole:dokter,pasien,admin');
//     // Admin Pasien CRUD
Route::resource('pasien', AdminPasienController::class)
    ->middleware('checkRole:admin');

// Admin CRUD
Route::resource('admin', AdminController::class)
    ->names('admin.pasien')
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
    
    Route::resource('dokter-layanan', DokterLayananController::class)
    ->names('dokter_layanan')
    ->middleware('checkRole:dokter,admin');
// Antrian routes
Route::get('antrian', [AntrianController::class, 'index'])
    ->name('antrian.index')
    ->middleware('checkRole:admin,pasien');
    Route::get('antrian/history', [AntrianController::class, 'history'])
    ->name('antrian.history')
    ->middleware('checkRole:pasien');
Route::post('antrian', [AntrianController::class, 'store'])
    ->name('antrian.store')
    ->middleware(['checkRole:pasien', 'profile.complete']);
Route::delete('antrian/{antrian}', [AntrianController::class, 'destroy'])
    ->name('antrian.destroy')
    ->middleware('checkRole:admin');
Route::patch('antrian/{antrian}/approve', [AntrianController::class, 'approve'])
    ->name('antrian.approve')
    ->middleware('checkRole:admin');

// Ajax endpoints for dependent dropdowns
Route::get('layanans/{layanan}/dokters', [AntrianController::class, 'dokters'])
    ->name('layanan.dokters')
    ->middleware('checkRole:admin,pasien');
Route::get('dokters/{dokter}/layanans/{layanan}/jadwals', [AntrianController::class, 'jadwals'])
    ->name('dokter.jadwals')
    ->middleware('checkRole:admin,pasien');

Route::get('payments/{antrian}/create', [PaymentsController::class, 'create'])
    ->name('payments.create')
    ->middleware('checkRole:pasien');
Route::post('payments/{antrian}', [PaymentsController::class, 'store'])
    ->name('payments.store')
   ->middleware('checkRole:pasien');

Route::get('profile', [ProfileController::class, 'edit'])
    ->name('profile.edit')
    ->middleware('checkRole:dokter,admin,pasien');
Route::patch('profile', [ProfileController::class, 'update'])
    ->name('profile.update')
    ->middleware('checkRole:dokter,admin,pasien');