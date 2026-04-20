<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public route - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes (require login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // Other pages (placeholder for now)
    Route::get('/properties', function () { return view('pages.dashboard'); })->name('properties.index');
    Route::get('/tenants', function () { return view('pages.dashboard'); })->name('tenants.index');
    Route::get('/leases', function () { return view('pages.dashboard'); })->name('leases.index');
    Route::get('/payments', function () { return view('pages.dashboard'); })->name('payments.index');
    Route::get('/maintenance', function () { return view('pages.dashboard'); })->name('maintenance.index');
    Route::get('/reports/payments', function () { return view('pages.dashboard'); })->name('reports.payments');
    Route::get('/reports/occupancy', function () { return view('pages.dashboard'); })->name('reports.occupancy');
    Route::get('/profile', function () { return view('pages.dashboard'); })->name('profile');
    Route::get('/settings', function () { return view('pages.dashboard'); })->name('settings');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';