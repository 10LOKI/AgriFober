<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CultureController;
use App\Http\Controllers\Api\InteractionIAController;
use App\Http\Controllers\Api\ParcelController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WeatherDataController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    // User Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Farmer Profile & Stats (accessible to farmer & admin)
    Route::prefix('farmer')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard']);
        Route::get('/profile', [AuthController::class, 'farmerProfile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });

    // Catalogue (read-only for all authenticated roles)
    // Reports (Rapports) — built incrementally
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/{report}/program', [ReportController::class, 'program']);
    Route::get('/reports/{report}/history', [ReportController::class, 'history']);
    Route::get('/reports/{report}', [ReportController::class, 'show']);

    Route::get('/cultures', [CultureController::class, 'index']);
    Route::get('/cultures/{culture}/associated', [CultureController::class, 'associated']);
    Route::get('/cultures/{culture}', [CultureController::class, 'show']);
    
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Parcels (full CRUD for owner + admin via policies)
    Route::apiResource('parcels', ParcelController::class);
    
    // Weather & recommendations (ownership enforced via ParcelPolicy)
    Route::get('/parcels/{parcel}/weather', [WeatherDataController::class, 'current']);
    Route::get('/parcels/{parcel}/weather/forecast', [WeatherDataController::class, 'forecast']);
    Route::get('/parcels/{parcel}/recommendations', [ParcelController::class, 'recommendations']);
    
    // IA Chat (all authenticated)
    Route::prefix('ai')->group(function () {
        Route::post('/chat', [InteractionIAController::class, 'chat']);
        Route::get('/history', [InteractionIAController::class, 'history']);
        Route::delete('/history/{id}', [InteractionIAController::class, 'destroy']);
        Route::post('/history/{id}/feedback', [InteractionIAController::class, 'feedback']);
    });

    // Admin routes (full management)
    Route::middleware('role:admin')->group(function () {
        // Full CRUD for cultures (admin can create/update/delete)
        Route::post('/cultures', [CultureController::class, 'store']);
        Route::put('/cultures/{culture}', [CultureController::class, 'update']);
        Route::delete('/cultures/{culture}', [CultureController::class, 'destroy']);
        
        // Full CRUD for products
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        
        // Admin weather access (all parcels)
        Route::get('/weather/parcel/{parcel}', [WeatherDataController::class, 'current']);
    });
});


