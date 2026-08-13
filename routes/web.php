<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\DeployController;
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

Route::post('/deploy/migrate', [DeployController::class, 'migrate'])
    ->middleware('throttle:5,1')
    ->name('deploy.migrate');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('applications', [AdminApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [AdminApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('applications.update-status');
        Route::get('applications/{application}/documents/{document}', [AdminDocumentController::class, 'show'])->name('applications.documents.show');
        Route::get('applications/{application}/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('applications.documents.download');
    });
});
