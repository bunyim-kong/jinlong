<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaseController extends Controller
{
    public function index()
    {
        return Lease::all();
    }

    public function show(Lease $lease)
    {
        return $lease;
    }

    // For Admin - Show ALL leases
    public function adminIndex()
    {
        $leases = Lease::with(['tenant.user', 'unit.property'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalLeases = Lease::count();
        $activeLeases = Lease::where('status', 'active')->count();
        $expiringLeases = Lease::where('status', 'active')
            ->where('end_date', '<=', Carbon::now()->addDays(30))
            ->count();
        $monthlyRevenue = Lease::where('status', 'active')->sum('monthly_rent');
        
        return view('pages.leases', compact('leases', 'totalLeases', 'activeLeases', 'expiringLeases', 'monthlyRevenue'));
    }

    // For Tenant - Show only their lease
    public function tenantLease()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        $lease = Lease::where('tenant_id', $tenant->id ?? 0)
            ->where('status', 'active')
            ->with('unit.property')
            ->first();
        
        return view('pages.leases', compact('lease'));
    }
}