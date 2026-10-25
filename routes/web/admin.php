<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BatteryController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::prefix('content/{page}/{locale}')
            ->where(['locale' => 'sq|en'])
            ->group(function (): void {
                Route::get('/', [ContentController::class, 'edit'])->name('content.edit');
                Route::put('/', [ContentController::class, 'update'])->name('content.update');
                Route::post('/reset', [ContentController::class, 'reset'])->name('content.reset');
            });

        Route::resource('catalog', BatteryController::class)->except('show')->parameters([
            'catalog' => 'battery',
        ]);

        Route::redirect('/settings', '/admin/content/settings/sq')->name('settings');
        Route::redirect('/products', '/admin/catalog')->name('products');
        Route::redirect('/services', '/admin/content/services/sq')->name('services');
        Route::redirect('/about', '/admin/content/about/sq')->name('about');
    });
});
