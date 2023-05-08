<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PresensiController;
use App\Models\Mahasiswa;
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

//root
Route::get('/', [LoginController::class, 'index']) -> name('login') -> middleware('guest');

//dashboard
Route::get('/dashboard', [DashboardController::class, "index"]) ->name('dashboard') -> middleware('auth');

//presensi
Route::get('/dashboard/presensi', [PresensiController::class, "index"]) -> middleware('auth');
Route::post('/dashboard/presensi/store', [PresensiController::class, "store"]) -> middleware('auth');


//database
Route::get('/dashboard/database', [DatabaseController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/database/mahasiswa', [MahasiswaController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/database/dosen', [DosenController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/database/jadwal', [JadwalController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/database/admin', [AdminController::class, "index"]) -> middleware("auth");

//kelas
Route::get('/dashboard/kelas', [KelasController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/kelas/{id}', [KelasController::class, "show"]) -> middleware("auth");
Route::post('/dashboard/kelas/{id}/generate', [KelasController::class, 'generateQRCode'])-> middleware("auth");
Route::put('/dashboard/kelas/{id}/update_jam', [KelasController::class, 'updateWaktuAbsen'])-> middleware("auth");
Route::post('/dashboard/kelas/{id}/sesi', [KelasController::class, 'gantiSesi'])-> middleware("auth");

//login
Route::get('/login', [LoginController::class, 'index']) -> name('login') -> middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);