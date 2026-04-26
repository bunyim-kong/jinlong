<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::all();
    }

    public function show(Payment $payment)
    {
        return $payment;
    }

    // For Admin - Show ALL payments
    public function adminIndex()
    {
        $payments = Payment::with(['lease.tenant.user', 'lease.unit.property'])
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
        
        $totalPaid = Payment::where('status', 'paid')->sum('amount');
        $totalPending = Payment::where('status', 'pending')->sum('amount');
        $totalOverdue = Payment::where('status', 'overdue')->sum('amount');
        
        return view('pages.payments', compact('payments', 'totalPaid', 'totalPending', 'totalOverdue'));
    }

    // For Tenant - Show only their payments
    public function tenantPayments()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        $totalPaid = 0;
        $totalPending = 0;
        $totalOverdue = 0;
        $payments = collect([]);
        
        if ($tenant) {
            $payments = Payment::where('tenant_id', $tenant->id)
                ->with(['lease.unit.property'])
                ->orderBy('payment_date', 'desc')
                ->paginate(10);
            
            $totalPaid = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'paid')
                ->sum('amount');
            
            $totalPending = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->sum('amount');
            
            $totalOverdue = Payment::where('tenant_id', $tenant->id)
                ->where('status', 'overdue')
                ->sum('amount');
        }
        
        return view('pages.payments', compact('payments', 'totalPaid', 'totalPending', 'totalOverdue'));
    }
}