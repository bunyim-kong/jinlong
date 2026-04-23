<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Models\Tenant;
use App\Models\Lease;
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
    
    Route::get('/leases', function () {
        $user = auth()->user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        $lease = Lease::where('tenant_id', $tenant->id ?? 0)
                    ->where('status', 'active')
                    ->with('unit.property')
                    ->first();
        return view('pages.leases', compact('lease'));
    })->name('leases.index');
    
    Route::get('/payments', function () { 
        return view('pages.payments'); 
    })->name('payments');
    
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

Route::get('/', function () {
    return redirect('/login');
});