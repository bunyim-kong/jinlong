<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LeaseController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/properties', function () { 
        return view('pages.dashboard'); 
    })->name('properties.index');
    
    Route::get('/tenants', function () { 
        return view('pages.dashboard'); 
    })->name('tenants.index');
    
    // Admin Lease Management - Dynamic
    Route::get('/leases', [LeaseController::class, 'adminIndex'])->name('leases.index');
    
    // Admin Payment Management - Dynamic
    Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments');
    
    Route::get('/maintenance', [App\Http\Controllers\MaintenanceRequestController::class, 'adminIndex'])->name('maintenance.index');
    
    Route::get('/reports/payments', function () { 
        return view('pages.dashboard'); 
    })->name('reports.payments');
    
    Route::get('/reports/occupancy', function () { 
        return view('pages.dashboard'); 
    })->name('reports.occupancy');
    
    Route::get('/profile', function () { 
        return view('pages.dashboard'); 
    })->name('profile');
    
    Route::get('/settings', function () { 
        return view('pages.dashboard'); 
    })->name('settings');
});

Route::get('/', function () {
    return redirect('/login');
});