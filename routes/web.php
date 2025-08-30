<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
