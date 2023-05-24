<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\PresensiController;
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
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');

//dashboard
Route::get('/dashboard', [DashboardController::class, "index"])->name('dashboard')->middleware('auth');

//presensi
Route::get('/dashboard/presensi', [PresensiController::class, "index"])->middleware('auth');
Route::post('/dashboard/presensi/store', [PresensiController::class, "store"])->middleware('auth');


//database
Route::get('/dashboard/database', [DatabaseController::class, "index"])->middleware("auth");

//database->mahasiswa
Route::get('/dashboard/database/mahasiswa', [MahasiswaController::class, "index"])->middleware("auth");
Route::post('/dashboard/database/mahasiswa/', [MahasiswaController::class, "store"])->middleware("auth");
Route::delete('/dashboard/database/mahasiswa/', [MahasiswaController::class, "destroy"])->middleware("auth");
Route::put('/dashboard/database/mahasiswa/', [MahasiswaController::class, "update"])->middleware("auth");

//database->dosen
Route::get('/dashboard/database/dosen', [DosenController::class, "index"])->middleware("auth");
Route::post('/dashboard/database/dosen/', [DosenController::class, "store"])->middleware("auth");
Route::delete('/dashboard/database/dosen/', [DosenController::class, "destroy"])->middleware("auth");
Route::put('/dashboard/database/dosen/', [DosenController::class, "update"])->middleware("auth");

//database->admin
Route::get('/dashboard/database/admin', [AdminController::class, "index"])->middleware("auth");
Route::post('/dashboard/database/admin/', [AdminController::class, "store"])->middleware("auth");
Route::delete('/dashboard/database/admin/', [AdminController::class, "destroy"])->middleware("auth");
Route::put('/dashboard/database/admin/', [AdminController::class, "update"])->middleware("auth");

//academic->jadwal
Route::get('/dashboard/academic/schedule', [JadwalController::class, "index"])->middleware("auth");
Route::post('/dashboard/academic/schedule', [JadwalController::class, "store"])->middleware("auth");
Route::delete('/dashboard/academic/schedule', [JadwalController::class, "destroy"])->middleware("auth");
Route::put('/dashboard/academic/schedule', [JadwalController::class, "update"])->middleware("auth");

//academic->kelas
Route::get('/dashboard/academic/class', [ClassController::class, "index"])->middleware("auth");
Route::get('/dashboard/academic/class/{id}', [ClassController::class, "show"])->middleware("auth");
Route::post('/dashboard/academic/class', [ClassController::class, "store"])->middleware("auth");
Route::delete('/dashboard/academic/class', [ClassController::class, "destroy"])->middleware("auth");
Route::post('/dashboard/academic/class/{id}', [ClassController::class, "update"])->middleware("auth");

//academic->matkul
Route::get('/dashboard/academic/course', [MatkulController::class, "index"])->middleware("auth");
Route::post('/dashboard/academic/course', [MatkulController::class, "store"])->middleware("auth");
Route::delete('/dashboard/academic/course', [MatkulController::class, "destroy"])->middleware("auth");
Route::post('/dashboard/academic/course/{id}', [MatkulController::class, "update"])->middleware("auth");

//kelas
Route::get('/dashboard/kelas', [KelasController::class, "index"])->middleware("auth");
Route::get('/dashboard/kelas/{id}', [KelasController::class, "show"])->middleware("auth");
Route::post('/dashboard/kelas/{id}/generate', [KelasController::class, 'generateQRCode'])->middleware("auth");
Route::put('/dashboard/kelas/{id}/update_jam', [KelasController::class, 'updateWaktuAbsen'])->middleware("auth");
Route::post('/dashboard/kelas/{id}/sesi', [KelasController::class, 'gantiSesi'])->middleware("auth");

//login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);
