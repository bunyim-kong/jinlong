<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // ========== ADMIN VIEW ==========
        if ($user->role === 'admin') {
            // Basic stats
            $totalProperties = Property::count();
            $totalTenants = Tenant::count();
            $totalUnits = Unit::count();
            
            // Monthly revenue (payments from current month)
            $monthlyRevenue = Payment::whereMonth('payment_date', date('m'))
                ->whereYear('payment_date', date('Y'))
                ->sum('amount');
            
            // Occupancy rate
            $occupiedUnits = Unit::where('status', 'rented')->count();
            $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;
            
            // Recent payments (last 5)
            $recentPayments = Payment::with(['lease.tenant.user'])
                ->latest()
                ->take(5)
                ->get();
            
            // Pending maintenance
            $pendingMaintenance = MaintenanceRequest::where('status', 'pending')
                ->orWhere('status', 'in_progress')
                ->with(['tenant.user', 'unit'])
                ->latest()
                ->take(4)
                ->get();
            
            // Expiring leases (next 30 days)
            $expiringLeases = Lease::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count();
            
            // Monthly revenue for chart (last 6 months)
            $months = [];
            $revenueData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $months[] = $month->format('M');
                
                $revenue = Payment::whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->sum('amount');
                
                $revenueData[] = $revenue;
            }
            
            return view('pages.dashboard', compact(
                'totalProperties',
                'totalTenants', 
                'monthlyRevenue',
                'occupancyRate',
                'recentPayments',
                'pendingMaintenance',
                'expiringLeases',
                'months',
                'revenueData'
            ));
        }
        
        // ========== TENANT VIEW ==========
        else if ($user->role === 'tenant') {
            // Get tenant record
            $tenant = Tenant::where('user_id', $user->id)->first();
            
            // Get current active lease
            $currentLease = null;
            $totalPaid = 0;
            $totalPending = 0;
            $openRequests = 0;
            $recentPayments = collect([]);
            
            if ($tenant) {
                $currentLease = Lease::where('tenant_id', $tenant->id)
                    ->where('status', 'active')
                    ->with(['unit.property'])
                    ->first();
                
                // Get payment totals
                $totalPaid = Payment::where('tenant_id', $tenant->id)
                    ->where('status', 'paid')
                    ->sum('amount');
                
                $totalPending = Payment::where('tenant_id', $tenant->id)
                    ->where('status', 'pending')
                    ->sum('amount');
                
                // Get open maintenance requests
                $openRequests = MaintenanceRequest::where('tenant_id', $tenant->id)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count();
                
                // Get recent payments
                $recentPayments = Payment::where('tenant_id', $tenant->id)
                    ->with(['lease.unit.property'])
                    ->latest()
                    ->take(5)
                    ->get();
            }
            
            return view('pages.dashboard', compact(
                'currentLease',
                'totalPaid',
                'totalPending',
                'openRequests',
                'recentPayments'
            ));
        }
        
        // Default fallback for unknown role
        return redirect('/login');
    }
}