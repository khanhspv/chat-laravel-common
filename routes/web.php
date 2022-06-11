<?php

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-room', [App\Http\Controllers\HomeController::class, 'getRoom'])->name('room-chat');
Route::post('/seed-msg', [App\Http\Controllers\HomeController::class, 'seedMsg'])->name('seed-msg');
Route::get('/loading-msg', [App\Http\Controllers\HomeController::class, 'loadingMsg'])->name('loading-msg');


