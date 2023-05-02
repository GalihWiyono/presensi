<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
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

//dashboard
Route::get('/', [LoginController::class, 'index']) -> name('login') -> middleware('guest');

Route::get('/dashboard', [DashboardController::class, "index"]) ->name('dashboard') -> middleware('auth');


//login
Route::get('/login', [LoginController::class, 'index']) -> name('login') -> middleware('guest');

Route::post('/login', [LoginController::class, 'authenticate']);

Route::post('/logout', [LoginController::class, 'logout']);

//register


//presensi
Route::get('/dashboard/presensi', [DashboardController::class, "presensi"]) -> middleware('auth');

//database
Route::get('/dashboard/database', function () {
    return view('dashboard/database');
});
