<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.store');
        Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('password.email');
        Route::get('reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
        Route::post('reset-password', [PasswordResetController::class, 'update'])
            ->middleware('throttle:5,1')
            ->name('password.update');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])
            ->middleware('permission:dashboard.view')
            ->name('dashboard');

        Route::get('applications/export', [AdminApplicationController::class, 'export'])
            ->middleware('permission:applications.export')
            ->name('applications.export');
        Route::post('applications/bulk-status', [AdminApplicationController::class, 'bulkUpdateStatus'])
            ->middleware('permission:applications.bulk')
            ->name('applications.bulk-status');
        Route::get('applications', [AdminApplicationController::class, 'index'])
            ->middleware('permission:applications.view')
            ->name('applications.index');
        Route::get('applications/{application}', [AdminApplicationController::class, 'show'])
            ->middleware('permission:applications.view')
            ->name('applications.show');
        Route::patch('applications/{application}/status', [AdminApplicationController::class, 'updateStatus'])
            ->middleware('permission:applications.update')
            ->name('applications.update-status');
        Route::get('applications/{application}/documents/{document}', [AdminDocumentController::class, 'show'])
            ->middleware('permission:documents.view')
            ->name('applications.documents.show');
        Route::get('applications/{application}/documents/{document}/download', [AdminDocumentController::class, 'download'])
            ->middleware('permission:documents.view')
            ->name('applications.documents.download');

        Route::middleware('permission:users.view')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
        });
        Route::middleware('permission:users.manage')->group(function () {
            Route::get('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        Route::middleware('permission:settings.view')->group(function () {
            Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        });
        Route::patch('settings', [SettingsController::class, 'update'])
            ->middleware('permission:settings.manage')
            ->name('settings.update');
    });
});
