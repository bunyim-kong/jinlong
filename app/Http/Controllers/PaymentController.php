<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
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

    // Add this method for tenant payment history
    public function userPayments()
    {
        $user = Auth::user();
        $tenant = Tenant::where('user_id', $user->id)->first();
        
        if (!$tenant) {
            return view('pages.payments', [
                'payments' => collect([]),
                'totalPaid' => 0,
                'totalPending' => 0,
                'totalOverdue' => 0
            ]);
        }
        
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
        
        return view('pages.payments', compact('payments', 'totalPaid', 'totalPending', 'totalOverdue'));
    }
}