<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ReportController;
use App\Http\Controllers\Pemda\PemdaDashboardController;
use App\Http\Controllers\Pemda\PemdaReportController;
use App\Http\Controllers\Api\LocationController;

// AUTH ROUTE
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::post('/register-process', [AuthController::class, 'register'])->name('register');    
Route::post('/login-process', [AuthController::class, 'login'])->name('login');    
Route::get('google/redirect', [AuthController::class, 'google_redirect'])->name('google.redirect');    
Route::get('auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/api/polygon/get', [LocationController::class, 'polygon']);

// GUEST ROUTE
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// REPORTS ROUTE
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

// PEMDA ROUTE
Route::middleware('pemda')->prefix('pemda')->name('pemda.')->group(function () {
    Route::get('/dashboard', [PemdaDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [PemdaReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [PemdaReportController::class, 'show'])
        ->whereNumber('id')
        ->name('reports.show');
    Route::post('/reports/{id}/update', [PemdaReportController::class, 'updateStatus'])->name('reports.updateStatus');
    Route::post('/reports/{id}/progress', [PemdaReportController::class, 'addProgress'])->name('reports.progress');
    Route::get('/profile', [PemdaProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [PemdaProfileController::class, 'update'])->name('profile.update');
});
