<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        return MaintenanceRequest::all();
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        return $maintenanceRequest;
    }
}
