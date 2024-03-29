<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalMahasiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\PendingController;
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

//set language
Route::get('/language/{locale}', [LocalizationController::class, "setLang"])->middleware('auth');


//presensi
Route::get('/dashboard/presensi', [PresensiController::class, "index"])->middleware('auth');
Route::post('/dashboard/presensi/store', [PresensiController::class, "store"])->middleware('auth');
Route::post('/dashboard/presensi/check', [PresensiController::class, "checkPresensi"])->middleware('auth');

//jadwal mahasiswa
Route::get('/dashboard/jadwal', [JadwalMahasiswaController::class, "index"])->middleware('auth');
Route::get('/dashboard/jadwal/{id}', [JadwalMahasiswaController::class, "show"])->middleware("auth");

//database
Route::get('/dashboard/database', [DatabaseController::class, "index"])->middleware("auth");

//database->mahasiswa
Route::get('/dashboard/database/mahasiswa', [MahasiswaController::class, "index"])->middleware("auth");
Route::get('/dashboard/database/mahasiswa/export', [MahasiswaController::class, "export"])->middleware("auth");
Route::post('/dashboard/database/mahasiswa/import', [MahasiswaController::class, "import"])->middleware("auth");
Route::post('/dashboard/database/mahasiswa/changePassword', [MahasiswaController::class, "changePassword"])->middleware("auth");
// Route::post('/dashboard/database/mahasiswa/', [MahasiswaController::class, "store"])->middleware("auth");
// Route::delete('/dashboard/database/mahasiswa/', [MahasiswaController::class, "destroy"])->middleware("auth");
// Route::put('/dashboard/database/mahasiswa/', [MahasiswaController::class, "update"])->middleware("auth");

//database->dosen
Route::get('/dashboard/database/dosen', [DosenController::class, "index"])->middleware("auth");
Route::get('/dashboard/database/dosen/export', [DosenController::class, "export"])->middleware("auth");
Route::post('/dashboard/database/dosen/import', [DosenController::class, "import"])->middleware("auth");
Route::post('/dashboard/database/dosen/changePassword', [DosenController::class, "changePassword"])->middleware("auth");
// Route::post('/dashboard/database/dosen/', [DosenController::class, "store"])->middleware("auth");
// Route::delete('/dashboard/database/dosen/', [DosenController::class, "destroy"])->middleware("auth");
// Route::put('/dashboard/database/dosen/', [DosenController::class, "update"])->middleware("auth");

//database->admin
Route::get('/dashboard/database/admin', [AdminController::class, "index"])->middleware("auth");
Route::get('/dashboard/database/admin/export', [AdminController::class, "export"])->middleware("auth");
Route::post('/dashboard/database/admin/import', [AdminController::class, "import"])->middleware("auth");
Route::post('/dashboard/database/admin/changePassword', [AdminController::class, "changePassword"])->middleware("auth");
// Route::post('/dashboard/database/admin/', [AdminController::class, "store"])->middleware("auth");
// Route::delete('/dashboard/database/admin/', [AdminController::class, "destroy"])->middleware("auth");
// Route::put('/dashboard/database/admin/', [AdminController::class, "update"])->middleware("auth");

//academic->jadwal
Route::get('/dashboard/academic/schedule', [JadwalController::class, "index"])->middleware("auth");
Route::get('/dashboard/academic/schedule/export', [JadwalController::class, "export"])->middleware("auth");
Route::post('/dashboard/academic/schedule/import', [JadwalController::class, "import"])->middleware("auth");
Route::get('/dashboard/academic/schedule/{id}/excel', [JadwalController::class, "reportPresensiExcel"])->middleware("auth");
Route::get('/dashboard/academic/schedule/{id}/pdf', [JadwalController::class, "generatePdf"])->middleware("auth");
Route::post('/dashboard/academic/schedule', [JadwalController::class, "store"])->middleware("auth");
Route::delete('/dashboard/academic/schedule', [JadwalController::class, "destroy"])->middleware("auth");
Route::put('/dashboard/academic/schedule', [JadwalController::class, "update"])->middleware("auth");

//academic->kelas
Route::get('/dashboard/academic/class', [ClassController::class, "index"])->middleware("auth");
Route::get('/dashboard/academic/class/export', [ClassController::class, "export"])->middleware("auth");
Route::post('/dashboard/academic/class/import', [ClassController::class, "import"])->middleware("auth");
Route::get('/dashboard/academic/class/{id}', [ClassController::class, "show"])->middleware("auth");
Route::get('/dashboard/academic/class/{id}/pdf', [ClassController::class, "generatePdf"])->middleware("auth");
Route::get('/dashboard/academic/class/{id}/excel', [ClassController::class, "reportKompensasiExcel"])->middleware("auth");
Route::get('/dashboard/academic/class/{id}/{nim}', [ClassController::class, "detail"])->middleware("auth");
Route::put('/dashboard/academic/class/{id}/{nim}', [ClassController::class, "updatePresensi"])->middleware("auth");
Route::post('/dashboard/academic/check', [ClassController::class, "getData"])->middleware("auth");
// Route::post('/dashboard/academic/class', [ClassController::class, "store"])->middleware("auth");
// Route::delete('/dashboard/academic/class', [ClassController::class, "destroy"])->middleware("auth");
// Route::post('/dashboard/academic/class/{id}', [ClassController::class, "update"])->middleware("auth");


//academic->matkul
Route::get('/dashboard/academic/course', [MatkulController::class, "index"])->middleware("auth");
Route::get('/dashboard/academic/course/export', [MatkulController::class, "export"])->middleware("auth");
Route::post('/dashboard/academic/course/import', [MatkulController::class, "import"])->middleware("auth");
// Route::post('/dashboard/academic/course', [MatkulController::class, "store"])->middleware("auth");
// Route::delete('/dashboard/academic/course', [MatkulController::class, "destroy"])->middleware("auth");
// Route::post('/dashboard/academic/course/{id}', [MatkulController::class, "update"])->middleware("auth");

//kelas
Route::get('/dashboard/kelas', [KelasController::class, "index"])->middleware("auth");
Route::get('/dashboard/kelas/{id}', [KelasController::class, "show"])->middleware("auth");
Route::post('/dashboard/kelas/{id}/generate', [KelasController::class, 'generateQRCode'])->middleware("auth");
Route::put('/dashboard/kelas/{id}/update_jam', [KelasController::class, 'updateWaktuAbsen'])->middleware("auth");
Route::post('/dashboard/kelas/{id}/sesi', [KelasController::class, 'gantiSesi'])->middleware("auth");
Route::post('/dashboard/kelas/tutup', [KelasController::class, 'closePekan'])->middleware("auth");
Route::post('/dashboard/kelas/pending', [KelasController::class, 'pendingPekan'])->middleware("auth");
Route::put('/dashboard/kelas/{id}/edit_presensi', [KelasController::class, 'editPresensi'])->middleware("auth");
Route::post('/dashboard/kelas/check', [KelasController::class, "checkNim"])->middleware('auth');
Route::post('/dashboard/kelas/{id}/presensi', [KelasController::class, "presence"])->middleware('auth');
Route::post('/dashboard/kelas/{id}/presensiOnline', [KelasController::class, "presensiOnline"])->middleware('auth');

//pending
Route::get('/dashboard/pending', [PendingController::class, "showPending"])->middleware("auth");
Route::put('/dashboard/pending', [PendingController::class, "updateDate"])->middleware("auth");
Route::post('/dashboard/pending/tutup', [PendingController::class, "closePendingWeek"])->middleware("auth");
Route::get('/dashboard/pending/{id}', [PendingController::class, "showDetailPending"])->middleware("auth");
Route::post('/dashboard/pending/checkPending', [PendingController::class, "checkPending"])->middleware("auth");

//account
Route::get('/dashboard/account/mahasiswa/changePassword', [AccountController::class, 'showChangePassword'])->middleware("auth");
Route::post('/dashboard/account/mahasiswa/changePassword', [AccountController::class, 'changePassword'])->middleware("auth");
Route::get('/dashboard/account/dosen/changePassword', [AccountController::class, 'showChangePassword'])->middleware("auth");
Route::post('/dashboard/account/dosen/changePassword', [AccountController::class, 'changePassword'])->middleware("auth");
Route::get('/dashboard/account/admin/changePassword', [AccountController::class, 'showChangePassword'])->middleware("auth");
Route::post('/dashboard/account/admin/changePassword', [AccountController::class, 'changePassword'])->middleware("auth");

//login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');;
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth');;
