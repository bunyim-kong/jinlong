@extends('layouts.app')

@section('title', 'Payment Management')
@section('page-title', 'Payment Management')

@section('content')

@php
    $user = Auth::user();
@endphp

@if($user->role === 'admin')

<!-- Search and Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" 
                       placeholder="Search by tenant, property or unit..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500">
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
            <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
                <i class="fas fa-redo mr-2"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Admin Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Total Paid</p><p class="text-3xl font-bold text-green-600">¥{{ number_format($totalPaid ?? 0) }}</p></div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Pending Payments</p><p class="text-3xl font-bold text-yellow-600">¥{{ number_format($totalPending ?? 0) }}</p></div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center"><i class="fas fa-clock text-yellow-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Overdue Payments</p><p class="text-3xl font-bold text-red-600">¥{{ number_format($totalOverdue ?? 0) }}</p></div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center"><i class="fas fa-exclamation-triangle text-red-600 text-xl"></i></div>
        </div>
    </div>
</div>

<!-- Admin Payment Table -->
<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="paymentsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tenant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Property / Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody id="paymentsTableBody">
                @forelse($payments ?? [] as $payment)
                <tr class="border-t hover:bg-gray-50 payment-row" data-status="{{ $payment->status }}" data-date="{{ $payment->payment_date }}">
                    <td class="px-6 py-4">{{ date('M d, Y', strtotime($payment->payment_date)) }}</td>
                    <td class="px-6 py-4">{{ $payment->lease->tenant->user->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4">{{ $payment->lease->unit->property->name ?? 'N/A' }} - Unit #{{ $payment->lease->unit->unit_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold">¥{{ number_format($payment->amount) }}</td>
                    <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs {{ $payment->status == 'paid' ? 'bg-green-100 text-green-700' : ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ ucfirst($payment->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No payments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">{{ ($payments ?? [])->links() }}</div>
</div>


{{-- ========== TENANT VIEW ========== --}}
@elseif($user->role === 'tenant')

<div class="space-y-6">
    <!-- Tenant Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Total Paid</p><p class="text-2xl font-bold text-green-600">¥{{ number_format($totalPaid ?? 0) }}</p></div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Pending Payments</p><p class="text-2xl font-bold text-yellow-600">¥{{ number_format($totalPending ?? 0) }}</p></div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Overdue</p><p class="text-2xl font-bold text-red-600">¥{{ number_format($totalOverdue ?? 0) }}</p></div>
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Tenant Payment Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b"><h3 class="text-lg font-semibold">My Payment History</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr><th class="px-6 py-3 text-left text-xs font-medium">Date</th><th class="px-6 py-3 text-left text-xs font-medium">Property</th><th class="px-6 py-3 text-left text-xs font-medium">Amount</th><th class="px-6 py-3 text-left text-xs font-medium">Status</th></tr>
                </thead>
                <tbody>
                    @forelse($payments ?? [] as $payment)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ date('M d, Y', strtotime($payment->payment_date)) }}</td>
                        <td class="px-6 py-4">{{ $payment->lease->unit->property->name ?? 'N/A' }} - Unit #{{ $payment->lease->unit->unit_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-semibold">¥{{ number_format($payment->amount) }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">{{ ucfirst($payment->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">{{ ($payments ?? [])->links() }}</div>
    </div>
</div>

@endif

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

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('monthFilter').value = '';
    filterTable();
}
</script>
@endsection