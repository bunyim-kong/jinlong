<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use App\Models\Lease;

Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes (require login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/properties', function () { 
        return view('pages.properties'); 
    })->name('properties');
    
    Route::get('/tenants', function () { 
        return view('pages.dashboard'); 
    })->name('tenants.index');
    
    Route::get('/leases', function () {
        $user = auth()->user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        $lease = Lease::where('tenant_id', $tenant->id ?? 0)
                    ->where('status', 'active')
                    ->with('unit.property')
                    ->first();
        
        return view('pages.leases', compact('lease'));
    })->name('leases');
    
    Route::get('/payments', function () { 
        return view('pages.dashboard'); 
    })->name('payments.index');
    
    Route::get('/maintenance', function () { 
        return view('pages.dashboard'); 
    })->name('maintenance.index');
    
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

// Auth routes (Breeze)
require __DIR__.'/auth.php';






































Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');