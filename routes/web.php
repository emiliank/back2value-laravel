<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/settings', [ContentController::class, 'settings'])->name('settings');
        Route::put('/settings', [ContentController::class, 'updateSettings'])->name('settings.update');

        Route::get('/products', [ContentController::class, 'products'])->name('products');
        Route::put('/products', [ContentController::class, 'updateProducts'])->name('products.update');

        Route::get('/services', [ContentController::class, 'services'])->name('services');
        Route::put('/services', [ContentController::class, 'updateServices'])->name('services.update');

        Route::get('/about', [ContentController::class, 'about'])->name('about');
        Route::put('/about', [ContentController::class, 'updateAbout'])->name('about.update');
    });
});
