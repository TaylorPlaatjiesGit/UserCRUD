<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }

    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::group(['middleware' => 'auth', 'prefix' => 'users'], function () {
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/update/{user}', [UserController::class, 'update'])->name('users.put');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('users.delete');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
