<?php

use App\Http\Controllers\DriverApplicationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DriverApplicationController::class, 'create'])->name('home');
Route::post('/', [DriverApplicationController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('applications.store');
Route::get('/apply/success/{reference}', [DriverApplicationController::class, 'success'])->name('applications.success');

Route::redirect('/apply', '/');
Route::get('/info', [HomeController::class, 'index'])->name('info');
