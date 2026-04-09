<?php

use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('leases', [LeaseController::class, 'index']);
Route::get('leases/{id}', [LeaseController::class, 'show']);

Route::get('maintenances', [MaintenanceRequestController::class, 'index']);
Route::get('maintenances/{maintenanceRequest}', [MaintenanceRequestController::class, 'show']);

Route::get('payments', [PaymentController::class, 'index']);
Route::get('payments/{id}', [PaymentController::class, 'show']);

Route::get('tenants', [TenantController::class, 'index']);
Route::get('tenants/{id}', [TenantController::class, 'show']);

Route::get('properties', [PropertyController::class, 'index']);
Route::get('properties/{id}', [PropertyController::class, 'show']);

Route::get('units', [UnitController::class, 'index']);
Route::get('units/{id}', [UnitController::class, 'show']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'show']);