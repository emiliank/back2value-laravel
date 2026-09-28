<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DiagnosticBookingController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegenerationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SolutionsController;
use App\Http\Controllers\SustainabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
Route::get('/solutions', [SolutionsController::class, 'index'])->name('solutions.index');
Route::get('/sustainability', [SustainabilityController::class, 'index'])->name('sustainability.index');
Route::get('/regeneration', [RegenerationController::class, 'index'])->name('regeneration.index');
Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');

Route::get('/diagnostics', [DiagnosticBookingController::class, 'create'])->name('diagnostics.create');
Route::post('/diagnostics/bookings', [DiagnosticBookingController::class, 'store'])->name('diagnostics.bookings.store');

Route::post('/diagnostics', [DiagnosticController::class, 'store'])->name('diagnostics.store');
Route::put('/diagnostics/{diagnostic}', [DiagnosticController::class, 'update'])->name('diagnostics.update');

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
