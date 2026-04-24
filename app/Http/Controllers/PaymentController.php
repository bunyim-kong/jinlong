<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function userPayments()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        // Default values
        $totalPaid = 0;
        $totalPending = 0;
        $totalOverdue = 0;
        
        if ($tenant) {
            // Use paginate() instead of get() - this gives you the links() method
            $payments = Payment::where('tenant_id', $tenant->id)
                ->with(['lease.unit.property'])
                ->orderBy('payment_date', 'desc')
                ->paginate(10);  // ← paginate() not get()
            
            $totalPaid = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'paid')
                ->sum('amount');
            
            $totalPending = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->sum('amount');
            
            $totalOverdue = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'overdue')
                ->sum('amount');
        } else {
            // If no tenant, create an empty paginator
            $payments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }
        
        return view('pages.payments', compact('payments', 'totalPaid', 'totalPending', 'totalOverdue'));
    }
}