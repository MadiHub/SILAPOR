<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\ReportController;

use App\Http\Controllers\Pemda\PemdaDashboardController;
use App\Http\Controllers\Pemda\PemdaReportController;
use App\Http\Controllers\Pemda\PemdaProfileController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDepartmentController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminAuditLogController;

use App\Http\Controllers\Api\LocationController;


// AUTH ROUTE
Route::get('/auth', [AuthController::class, 'index'])->name('auth');
Route::post('/register-process', [AuthController::class, 'register'])->name('register');    
Route::post('/login-process', [AuthController::class, 'login'])->name('login');    
Route::get('google/redirect', [AuthController::class, 'google_redirect'])->name('google.redirect');    
Route::get('auth/google/callback', [AuthController::class, 'google_callback'])->name('google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
Route::put('/profile', [AuthController::class, 'profileUpdate'])->name('profile.update');

// FORGOT PASSWORD ROUTES
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.verify-otp');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.check-otp');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset-form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// GUEST ROUTE
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/statistics', [HomeController::class, 'statistics'])->name('statistics');

// POLLYGON API
Route::get('/api/polygon/get', [LocationController::class, 'polygon']);

// REPORTS ROUTE
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::middleware('warga')->prefix('reports')->name('reports.')->group(function () {
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

// ADMIN ROUTE
Route::middleware('admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------
    // Manajemen Pengguna  →  tabel: users, user_departments
    // ----------------------------------------------------------
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',              [AdminUserController::class, 'index'])       ->name('index');
        Route::get('/create',        [AdminUserController::class, 'create'])      ->name('create');
        Route::post('/',             [AdminUserController::class, 'store'])       ->name('store');
        Route::get('/{id}',          [AdminUserController::class, 'show'])        ->name('show')   ->whereNumber('id');
        Route::get('/{id}/edit',     [AdminUserController::class, 'edit'])        ->name('edit')   ->whereNumber('id');
        Route::put('/{id}',          [AdminUserController::class, 'update'])      ->name('update') ->whereNumber('id');
        Route::delete('/{id}',       [AdminUserController::class, 'destroy'])     ->name('destroy')->whereNumber('id');

        // Ubah status  →  kolom: users.status ENUM('active','inactive','banned')
        Route::post('/{id}/ban',     [AdminUserController::class, 'ban'])         ->name('ban')    ->whereNumber('id');
        Route::post('/{id}/unban',   [AdminUserController::class, 'unban'])       ->name('unban')  ->whereNumber('id');
        Route::post('/{id}/suspend', [AdminUserController::class, 'suspend'])     ->name('suspend')->whereNumber('id');

        // Ubah role  →  kolom: users.role ENUM('admin','warga','pemda')
        Route::post('/{id}/role',    [AdminUserController::class, 'changeRole'])  ->name('role')   ->whereNumber('id');

        // Kelola dinas  →  tabel: user_departments
        Route::post('/{id}/departments',             [AdminUserController::class, 'assignDepartment']) ->name('departments.assign') ->whereNumber('id');
        Route::delete('/{id}/departments/{deptId}',  [AdminUserController::class, 'removeDepartment'])->name('departments.remove') ->whereNumber('id')->whereNumber('deptId');
    });

    // ----------------------------------------------------------
    // Manajemen Dinas  →  tabel: departments
    // ----------------------------------------------------------
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/',          [AdminDepartmentController::class, 'index'])  ->name('index');
        Route::get('/create',    [AdminDepartmentController::class, 'create']) ->name('create');
        Route::post('/',         [AdminDepartmentController::class, 'store'])  ->name('store');
        Route::get('/{id}',      [AdminDepartmentController::class, 'show'])   ->name('show')  ->whereNumber('id');
        Route::get('/{id}/edit', [AdminDepartmentController::class, 'edit'])   ->name('edit')  ->whereNumber('id');
        Route::put('/{id}',      [AdminDepartmentController::class, 'update']) ->name('update')->whereNumber('id');
        Route::delete('/{id}',   [AdminDepartmentController::class, 'destroy'])->name('destroy')->whereNumber('id');
    });

    // ----------------------------------------------------------
    // Manajemen Kategori  →  tabel: problem_categories
    // ----------------------------------------------------------
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',          [AdminCategoryController::class, 'index'])  ->name('index');
        Route::get('/create',    [AdminCategoryController::class, 'create']) ->name('create');
        Route::post('/',         [AdminCategoryController::class, 'store'])  ->name('store');
        Route::get('/{id}',      [AdminCategoryController::class, 'show'])   ->name('show')  ->whereNumber('id');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])   ->name('edit')  ->whereNumber('id');
        Route::put('/{id}',      [AdminCategoryController::class, 'update']) ->name('update')->whereNumber('id');
        Route::delete('/{id}',   [AdminCategoryController::class, 'destroy'])->name('destroy')->whereNumber('id');

        // Pindah kategori ke dinas lain  →  kolom: problem_categories.department_id
        Route::post('/{id}/remap', [AdminCategoryController::class, 'remap'])->name('remap')->whereNumber('id');
    });

    // ----------------------------------------------------------
    // Moderasi Laporan  →  tabel: reports, report_updates, report_images
    // ----------------------------------------------------------
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',        [AdminReportController::class, 'index'])         ->name('index');
        Route::get('/{id}',    [AdminReportController::class, 'show'])          ->name('show')    ->whereNumber('id');
        Route::delete('/{id}', [AdminReportController::class, 'destroy'])       ->name('destroy') ->whereNumber('id');

        // Override status  →  tabel: report_updates (updated_by = admin id)
        Route::post('/{id}/status',   [AdminReportController::class, 'overrideStatus'])->name('status')  ->whereNumber('id');

        // Reassign dinas  →  kolom: reports.department_id
        Route::post('/{id}/reassign', [AdminReportController::class, 'reassign'])      ->name('reassign')->whereNumber('id');

        // Tambah progress  →  tabel: report_updates
        Route::post('/{id}/progress', [AdminReportController::class, 'addProgress'])   ->name('progress')->whereNumber('id');
    });

    // ----------------------------------------------------------
    // Statistik  →  aggregasi dari: reports, report_votes, report_updates
    // ----------------------------------------------------------
    Route::prefix('stats')->name('stats.')->group(function () {
        Route::get('/',            [AdminStatsController::class, 'overview'])   ->name('overview');
        Route::get('/trends',      [AdminStatsController::class, 'trends'])     ->name('trends');
        Route::get('/departments', [AdminStatsController::class, 'departments'])->name('departments');
        Route::get('/top-votes',   [AdminStatsController::class, 'topVotes'])   ->name('top-votes');
        Route::get('/export',      [AdminStatsController::class, 'export'])     ->name('export');
    });

    // ----------------------------------------------------------
    // Audit Log  →  tabel: report_updates (kolom updated_by)
    //               + users (kolom last_login_at)
    // ----------------------------------------------------------
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/',             [AdminAuditLogController::class, 'index'])   ->name('index');
        Route::get('/users/{id}',   [AdminAuditLogController::class, 'byUser'])  ->name('by-user')  ->whereNumber('id');
        Route::get('/reports/{id}', [AdminAuditLogController::class, 'byReport'])->name('by-report')->whereNumber('id');
    });

});