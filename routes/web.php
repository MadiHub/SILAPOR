<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ReportController;
use App\Http\Controllers\Api\LocationController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::middleware('warga')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
    Route::post('/{id}/vote', [ReportController::class, 'vote'])->name('vote');
    Route::get('/me', [ReportController::class, 'myReports'])->name('me');

    Route::get('/{id}/edit', [ReportController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ReportController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReportController::class, 'destroy'])->name('destroy');
});
// AUTH
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::post('/register-process', [AuthController::class, 'register'])->name('register');    
Route::post('/login-process', [AuthController::class, 'login'])->name('login');    
Route::get('google/redirect', [AuthController::class, 'google_redirect'])->name('google.redirect');    
Route::get('auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/api/polygon/get', [LocationController::class, 'polygon']);
