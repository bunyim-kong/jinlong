<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard route
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// Property Management Routes
Route::get('/properties', function () {
    return view('pages.properties');
})->name('properties.index');

Route::get('/properties/create', function () {
    return view('pages.properties');
})->name('properties.create');

Route::get('/properties/{id}/edit', function ($id) {
    return view('pages.properties');
})->name('properties.edit');

// Other placeholder routes - create actual views for these
Route::get('/tenants', function () { 
    return view('pages.tenants'); // Create this view file
})->name('tenants.index');

Route::get('/leases', function () { 
    return view('pages.leases'); // Create this view file
})->name('leases.index');

Route::get('/payments', function () { 
    return view('pages.payments'); // Create this view file
})->name('payments.index');

Route::get('/maintenance', function () { 
    return view('pages.maintenance'); // Create this view file
})->name('maintenance.index');

Route::get('/reports/payments', function () { 
    return view('pages.reports-payments'); // Create this view file
})->name('reports.payments');

Route::get('/reports/occupancy', function () { 
    return view('pages.reports-occupancy'); // Create this view file
})->name('reports.occupancy');

Route::get('/profile', function () { 
    return view('pages.profile'); // Create this view file
})->name('profile');

Route::get('/settings', function () { 
    return view('pages.settings'); // Create this view file
})->name('settings');

// Logout route
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

// Home route (optional)
Route::get('/', function () {
    return redirect()->route('dashboard');
});