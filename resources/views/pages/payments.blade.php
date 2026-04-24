@extends('layouts.app')

@section('title', 'My Payment History')
@section('page-title', 'Payment History')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Paid -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Paid</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">¥{{ number_format($totalPaid) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Payments</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">¥{{ number_format($totalPending) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Overdue Payments -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Overdue Payments</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">¥{{ number_format($totalOverdue) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Payment History</h3>
            <p class="text-sm text-gray-500 mt-1">All your rental payments</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ date('M d, Y', strtotime($payment->payment_date)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->lease->unit->property->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            Unit {{ $payment->lease->unit->unit_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ¥{{ number_format($payment->amount) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'overdue' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->status == 'paid')
                            <button onclick="downloadReceipt({{ $payment->id }})" 
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-download"></i>
                            </button>
                            @else
                            <button onclick="payNow({{ $payment->id }})" 
                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                Pay Now
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-receipt text-4xl mb-3 block"></i>
                            <p>No payment records found.</p>
                            <p class="text-sm mt-1">Your payment history will appear here once you make a payment.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Payment Tips -->
    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
            <div>
                <p class="font-semibold text-blue-800">Payment Information</p>
                <p class="text-sm text-blue-700 mt-1">
                    Rent is due on the 1st of each month. A 5% late fee will be applied after the 5th.
                    For any payment issues, please contact the property manager.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadReceipt(paymentId) {
        alert('Receipt for payment #' + paymentId + ' will be downloaded');
        // Implement actual receipt download
    }
    
    function payNow(paymentId) {
        alert('Redirect to payment gateway for payment #' + paymentId);
        // Implement actual payment gateway
    }
</script>
@endsection