<?php

use App\Http\Controllers\Web\DiagnosticBookingController;
use App\Http\Controllers\Web\DiagnosticController;
use App\Http\Controllers\Web\LandingController;
use App\Http\Controllers\Web\LocaleRedirectController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\RegenerationController;
use App\Http\Controllers\Web\ResourceController;
use App\Http\Controllers\Web\ServicesController;
use App\Http\Controllers\Web\SolutionsController;
use App\Http\Controllers\Web\SustainabilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', LocaleRedirectController::class)->name('root');

Route::prefix('{locale}')
    ->where(['locale' => 'sq|en'])
    ->group(function (): void {
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
    });
