<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CultureController as AdminCultureController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Debug: list test users (temporary)
Route::get('/debug-users', function() {
    $users = \App\Models\User::whereIn('email', [
        'superadmin@agrifober.com',
        'tech@test.com',
        'farmer@test.com',
        'admin@agrifober.com'
    ])->get(['id','username','email','role','is_approved']);
    
    return response()->json($users);
});

// Public Routes
Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin Routes (protected - any authenticated user can access for now)
Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    
    // Cultures
    Route::resource('cultures', AdminCultureController::class);
    
    // Products
    Route::resource('products', AdminProductController::class);
    
});
