<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');
    
    // Admin only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('properties', PropertyController::class);
        Route::resource('units', UnitController::class);
        Route::resource('tenants', TenantController::class);
        Route::resource('leases', LeaseController::class);
    });
    
    // Tenant only routes
    Route::middleware(['role:tenant'])->group(function () {
        Route::get('/my-rental', [TenantController::class, 'myRental']);
        Route::get('/my-payments', [TenantController::class, 'myPayments']);
        Route::resource('maintenance', MaintenanceRequestController::class)->only(['index', 'create', 'store']);
    });
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});

require __DIR__.'/auth.php';
