<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\MaintenanceRequestController;
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
    
    Route::get('/properties', function () { return view('pages.dashboard'); })->name('properties.index');
    Route::get('/tenants', function () { return view('pages.dashboard'); })->name('tenants.index');
    
    Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');
    Route::post('/lease/renewal', [LeaseController::class, 'requestRenewal'])->name('lease.renewal');
    
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    
    Route::get('/maintenance', [MaintenanceRequestController::class, 'index'])->name('maintenance.index');
    
    Route::post('/maintenance/{id}/approve', [MaintenanceRequestController::class, 'approve'])->name('maintenance.approve');
    Route::post('/maintenance/{id}/complete', [MaintenanceRequestController::class, 'complete'])->name('maintenance.complete');
    Route::post('/maintenance/{id}/cancel', [MaintenanceRequestController::class, 'cancel'])->name('maintenance.cancel');
    Route::get('/maintenance/{id}/json', [MaintenanceRequestController::class, 'show'])->name('maintenance.show.json');
    Route::get('/maintenance/stats', [MaintenanceRequestController::class, 'getStats'])->name('maintenance.stats');
    Route::post('/maintenance/store', [MaintenanceRequestController::class, 'tenantStore'])->name('maintenance.store');
    
    Route::get('/reports/payments', function () { return view('pages.dashboard'); })->name('reports.payments');
    Route::get('/reports/occupancy', function () { return view('pages.dashboard'); })->name('reports.occupancy');
    Route::get('/profile', function () { return view('pages.dashboard'); })->name('profile');
    Route::get('/settings', function () { return view('pages.dashboard'); })->name('settings');
});

Route::get('/', function () {
    return redirect('/login');
});