<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;



Route::get('/', [HomeController::class, 'index'])->name('home.index');

// AUTH
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::post('/register-process', [AuthController::class, 'register'])->name('register');    
Route::post('/login-process', [AuthController::class, 'login'])->name('login');    
Route::get('google/redirect', [AuthController::class, 'google_redirect'])->name('google.redirect');    
Route::get('auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
