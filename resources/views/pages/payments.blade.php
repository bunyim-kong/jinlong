@extends('layouts.app')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Paid</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">¥{{ number_format($totalPaid ?? 0) }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> +8% from last month
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Payments</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">¥{{ number_format($totalPending ?? 0) }}</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-yellow-600">
                <i class="fas fa-calendar mr-1"></i> Awaiting confirmation
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Overdue Payments</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">¥{{ number_format($totalOverdue ?? 0) }}</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-red-600">
                <i class="fas fa-bell mr-1"></i> Requires attention
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" onkeyup="filterTable()" 
                           placeholder="Search by tenant, property or unit..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                </div>
            </div>
            <div>
                <select id="statusFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 bg-white">
                    <option value="all">All Status</option>
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div>
                <input type="month" id="monthFilter" onchange="filterTable()" 
                       class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500">
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="paymentsTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments ?? [] as $payment)
                    <tr class="hover:bg-gray-50 transition-colors payment-row" data-status="{{ $payment->status }}" data-date="{{ $payment->payment_date }}">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-800">{{ date('M d, Y', strtotime($payment->payment_date)) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $payment->lease->tenant->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $payment->lease->tenant->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $payment->lease->unit->property->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg">#{{ $payment->lease->unit->unit_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 text-lg">¥{{ number_format($payment->amount) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-lg capitalize">
                                <i class="fas {{ $payment->payment_method == 'bank_transfer' ? 'fa-university' : ($payment->payment_method == 'credit_card' ? 'fa-credit-card' : 'fa-money-bill') }} mr-1"></i>
                                {{ str_replace('_', ' ', $payment->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = $payment->status == 'paid' ? 'bg-green-100 text-green-700' : ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                $statusIcon = $payment->status == 'paid' ? 'fa-check-circle' : ($payment->status == 'pending' ? 'fa-clock' : 'fa-exclamation-circle');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }} mr-1"></i>
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <i class="fas fa-receipt text-5xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-500">No payments found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
            <p class="text-sm text-gray-500">Showing {{ $payments->count() ?? 0 }} of {{ $payments->total() ?? 0 }} payments</p>
            <div>
                {{ ($payments ?? [])->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const monthValue = document.getElementById('monthFilter').value;
    const rows = document.querySelectorAll('.payment-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status');
        const date = row.getAttribute('data-date');
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusValue === 'all' || status === statusValue;
        const matchesMonth = !monthValue || date.startsWith(monthValue);
        
        row.style.display = matchesSearch && matchesStatus && matchesMonth ? '' : 'none';
    });
}
</script>
@endsection