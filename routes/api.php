<?php

use App\Http\Controllers\Api\BatteryController;
use App\Http\Controllers\Api\MaintenanceInquiryController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function (): void {
    Route::get('/batteries', [BatteryController::class, 'index'])->name('api.batteries.index');
    Route::post('/maintenance-inquiries', [MaintenanceInquiryController::class, 'store'])->name('api.maintenance-inquiries.store');
});
