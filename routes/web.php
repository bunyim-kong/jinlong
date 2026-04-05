<?php

use Illuminate\Support\Facades\Route;

// Temporary routes for testing layout
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// Placeholder routes to avoid errors
Route::get('/properties', function () { return view('dashboard'); })->name('properties.index');
Route::get('/tenants', function () { return view('dashboard'); })->name('tenants.index');
Route::get('/leases', function () { return view('dashboard'); })->name('leases.index');
Route::get('/payments', function () { return view('dashboard'); })->name('payments.index');
Route::get('/maintenance', function () { return view('dashboard'); })->name('maintenance.index');
Route::get('/reports/payments', function () { return view('dashboard'); })->name('reports.payments');
Route::get('/reports/occupancy', function () { return view('dashboard'); })->name('reports.occupancy');
Route::get('/profile', function () { return view('dashboard'); })->name('profile');
Route::get('/settings', function () { return view('dashboard'); })->name('settings');

// Logout placeholder
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');