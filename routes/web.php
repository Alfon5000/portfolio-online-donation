<?php

use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DonationController::class, 'index'])->name('index');
Route::get('/create', [DonationController::class, 'create'])->name('create');

// API Routes
Route::prefix('/api')->group(function () {
    Route::post('/donate', [DonationController::class, 'store'])->name('store');
    Route::post('/midtrans/notification', [DonationController::class, 'notification']);
});
