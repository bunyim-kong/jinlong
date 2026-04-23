<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\Lease;

Route::get('/', function () {
    return view('welcome'); // change if you have a custom homepage
});

// Dashboard
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// Properties
Route::get('/properties', function () { 
    return view('pages.properties'); 
})->name('properties');

Route::post('/properties', [PropertyController::class, 'store'])
    ->name('properties.store');

// Tenants
Route::get('/tenants', function () { 
    return view('pages.tenants'); 
})->name('tenants.index');

// Leases (NO auth user now)
Route::get('/leases', function () {

    // Since auth is removed, just fetch sample or first record
    $tenant = Tenant::first();

    $lease = null;

    if ($tenant) {
        $lease = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with('unit.property')
            ->first();
    }

    return view('pages.leases', compact('lease'));

})->name('leases');

// Payments
Route::get('/payments', function () { 
    return view('pages.payments'); 
})->name('payments.index');

// Maintenance
Route::get('/maintenance', function () { 
    return view('pages.maintenance'); 
})->name('maintenance.index');

// Reports
Route::get('/reports/payments', function () { 
    return view('pages.reports-payments'); 
})->name('reports.payments');

Route::get('/reports/occupancy', function () { 
    return view('pages.reports-occupancy'); 
})->name('reports.occupancy');

// Profile
Route::get('/profile', function () { 
    return view('pages.profile'); 
})->name('profile');

// Settings
Route::get('/settings', function () { 
    return view('pages.settings'); 
})->name('settings');