<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
{
       return Tenant::all();
}
    public function show(Tenant $tenant)
    {
        return $tenant;
    }
}