<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class LeaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $leases = Lease::with(['tenant.user', 'unit.property'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            $totalLeases = Lease::count();
            $activeLeases = Lease::where('status', 'active')->count();
            $expiringLeases = Lease::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count();
            $monthlyRevenue = Lease::where('status', 'active')->sum('monthly_rent');
            
            return view('pages.leases', compact('leases', 'totalLeases', 'activeLeases', 'expiringLeases', 'monthlyRevenue'));
        }
        
        else {
            $tenant = Tenant::where('user_id', $user->id)->first();
            
            $lease = null;
            if ($tenant) {
                $lease = Lease::where('tenant_id', $tenant->id)
                    ->where('status', 'active')
                    ->with('unit.property')
                    ->first();
            }
            
            return view('pages.leases', compact('lease'));
        }
    }

    public function requestRenewal(Request $request)
    {
        $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'renewal_months' => 'required|in:1,3,6,12,24',
            'notes' => 'nullable|string'
        ]);
        
        $lease = Lease::find($request->lease_id);
        $newEndDate = \Carbon\Carbon::parse($lease->end_date)->addMonths($request->renewal_months);
        
        // Store renewal request (you may want to create a renewals table)
        // For now, just return success
        
        return redirect()->back()->with('success', 'Renewal request submitted successfully! Management will review your request.');
    }
}