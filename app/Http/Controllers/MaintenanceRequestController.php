<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $maintenanceRequests = MaintenanceRequest::with(['tenant.user', 'unit.property'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            $totalRequests = MaintenanceRequest::count();
            $pendingRequests = MaintenanceRequest::where('status', 'pending')->count();
            $inProgressRequests = MaintenanceRequest::where('status', 'in_progress')->count();
            $completedRequests = MaintenanceRequest::where('status', 'completed')->count();
            
            return view('pages.maintenance', compact('maintenanceRequests', 'totalRequests', 'pendingRequests', 'inProgressRequests', 'completedRequests'));
        }
        
        else {
            $tenant = Tenant::where('user_id', $user->id)->first();
            
            $units = collect([]);
            $maintenanceRequests = collect([]);
            $pendingCount = 0;
            $inProgressCount = 0;
            $completedCount = 0;
            
            if ($tenant) {
                $units = Unit::whereHas('leases', function($q) use ($tenant) {
                    $q->where('tenant_id', $tenant->id)->where('status', 'active');
                })->get();
                
                $maintenanceRequests = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->with('unit.property')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                
                $pendingCount = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->where('status', 'pending')
                    ->count();
                
                $inProgressCount = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->where('status', 'in_progress')
                    ->count();
                
                $completedCount = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->where('status', 'completed')
                    ->count();
            }
            
            return view('pages.maintenance', compact('maintenanceRequests', 'units', 'pendingCount', 'inProgressCount', 'completedCount'));
        }
    }

    public function approve($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        $request->status = 'in_progress';
        $request->save();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'in_progress']);
        }
        return redirect()->back()->with('success', 'Request approved');
    }

    public function complete($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        $request->status = 'completed';
        $request->completed_date = now();
        $request->save();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'completed']);
        }
        return redirect()->back()->with('success', 'Request completed');
    }

    public function cancel($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        $request->status = 'cancelled';
        $request->save();
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'status' => 'cancelled']);
        }
        return redirect()->back()->with('success', 'Request cancelled');
    }

    public function tenantStore(Request $request)
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'unit_id' => 'required|exists:units,id',
        ]);
        
        MaintenanceRequest::create([
            'tenant_id' => $tenant->id,
            'unit_id' => $request->unit_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'request_date' => now(),
            'status' => 'pending',
        ]);
        
        return redirect()->route('maintenance.index')->with('success', 'Maintenance request submitted successfully');
    }
    
    public function getStats()
    {
        return response()->json([
            'total' => MaintenanceRequest::count(),
            'pending' => MaintenanceRequest::where('status', 'pending')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'completed' => MaintenanceRequest::where('status', 'completed')->count(),
        ]);
    }

    public function show($id)
    {
        $request = MaintenanceRequest::with(['tenant.user', 'unit.property'])->findOrFail($id);
        return response()->json($request);
    }
}