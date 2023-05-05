<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
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
Route::get('/', [LoginController::class, 'index']) -> name('login') -> middleware('guest');

//dashboard
Route::get('/dashboard', [DashboardController::class, "index"]) ->name('dashboard') -> middleware('auth');

//presensi
Route::get('/dashboard/presensi', [PresensiController::class, "index"]) -> middleware('auth');

//database
Route::get('/dashboard/database', [DatabaseController::class, "index"]) -> middleware("auth");

//kelas
Route::get('/dashboard/kelas', [KelasController::class, "index"]) -> middleware("auth");
Route::get('/dashboard/kelas/{id}', [KelasController::class, "show"]) -> middleware("auth");
Route::post('/dashboard/kelas/{id}/presence', [KelasController::class, 'presence'])-> middleware("auth");;

//login
Route::get('/login', [LoginController::class, 'index']) -> name('login') -> middleware('guest');

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout']);