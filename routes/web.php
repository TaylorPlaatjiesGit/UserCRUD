<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::post('/store', [UserController::class, 'store'])->name('users.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
