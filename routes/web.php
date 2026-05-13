<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CultureController as AdminCultureController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RegionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

    // Cultures
    Route::resource('cultures', AdminCultureController::class);

    // Products
    Route::resource('products', AdminProductController::class);

    // Regions
    Route::resource('regions', RegionController::class)->except(['show']);
});