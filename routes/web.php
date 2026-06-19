<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home.index');
