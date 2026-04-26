<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

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

    // Add this method for admin page
    public function adminIndex()
    {
        $maintenanceRequests = MaintenanceRequest::with(['tenant.user', 'unit.property'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalRequests = MaintenanceRequest::count();
        $pendingRequests = MaintenanceRequest::where('status', 'pending')->count();
        $inProgressRequests = MaintenanceRequest::where('status', 'in_progress')->count();
        $completedRequests = MaintenanceRequest::where('status', 'completed')->count();
        
        return view('pages.maintenance', compact('maintenanceRequests', 'totalRequests', 'pendingRequests', 'inProgressRequests', 'completedRequests'));
    }
}