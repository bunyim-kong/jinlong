@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@php
    $user = Auth::user();
@endphp

{{-- ========== ADMIN VIEW ========== --}}
@if($user->role === 'admin')

    <!-- Welcome Banner - Admin -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 mb-8 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold mb-2">Welcome back, {{ $user->name }}!</h2>
                <p class="text-blue-100">Here's what's happening with your properties today.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-5xl text-blue-200 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Admin -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-600 transition-colors duration-300">
                    <i class="fas fa-building text-2xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <span class="text-3xl font-bold text-gray-800">{{ $totalProperties ?? 0 }}</span>
            </div>
            <h3 class="text-gray-600 font-medium mb-1">Total Properties</h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-xl group-hover:bg-green-600 transition-colors duration-300">
                    <i class="fas fa-users text-2xl text-green-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <span class="text-3xl font-bold text-gray-800">{{ $totalTenants ?? 0 }}</span>
            </div>
            <h3 class="text-gray-600 font-medium mb-1">Active Tenants</h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-xl group-hover:bg-purple-600 transition-colors duration-300">
                    <i class="fas fa-wallet text-2xl text-purple-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <span class="text-3xl font-bold text-gray-800">¥{{ number_format($monthlyRevenue ?? 0) }}</span>
            </div>
            <h3 class="text-gray-600 font-medium mb-1">Monthly Revenue</h3>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-orange-100 rounded-xl group-hover:bg-orange-600 transition-colors duration-300">
                    <i class="fas fa-chart-pie text-2xl text-orange-600 group-hover:text-white transition-colors duration-300"></i>
                </div>
                <span class="text-3xl font-bold text-gray-800">{{ $occupancyRate ?? 0 }}%</span>
            </div>
            <h3 class="text-gray-600 font-medium mb-1">Occupancy Rate</h3>
        </div>
    </div>

    <!-- Revenue Chart - Admin -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
                    <p class="text-sm text-gray-500 mt-1">Monthly revenue trends</p>
                </div>
            </div>
            <canvas id="revenueChart" height="250"></canvas>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="w-full flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100">
                    <span>Add New Property</span>
                    <i class="fas fa-arrow-right text-blue-600"></i>
                </a>
                <a href="#" class="w-full flex items-center justify-between p-3 bg-green-50 rounded-xl hover:bg-green-100">
                    <span>Register New Tenant</span>
                    <i class="fas fa-arrow-right text-green-600"></i>
                </a>
                <a href="{{ route('leases.index') }}" class="w-full flex items-center justify-between p-3 bg-purple-50 rounded-xl hover:bg-purple-100">
                    <span>View All Leases</span>
                    <i class="fas fa-arrow-right text-purple-600"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Payments Table - Admin -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tenant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments ?? [] as $payment)
                        <tr class="border-t">
                            <td class="px-6 py-4">{{ $payment->tenant->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4">¥{{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-4">{{ $payment->payment_date }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No payments yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Maintenance Requests</h3>
            </div>
            <div class="divide-y">
                @forelse($pendingMaintenance ?? [] as $request)
                <div class="p-4">
                    <p class="font-medium">{{ $request->title }}</p>
                    <p class="text-sm text-gray-500">Unit #{{ $request->unit->unit_number ?? 'N/A' }}</p>
                </div>
                @empty
                <div class="p-8 text-center text-gray-500">No pending requests</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Expiring Leases Alert - Admin -->
    @if(($expiringLeases ?? 0) > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
        <p class="font-semibold text-yellow-800">⚠️ {{ $expiringLeases }} Leases Expiring Soon</p>
    </div>
    @endif


{{-- ========== TENANT VIEW ========== --}}
@elseif($user->role === 'tenant')

    <!-- Welcome Banner - Tenant -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl shadow-lg p-6 mb-8 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold mb-2">Welcome back, {{ $user->name }}!</h2>
                <p class="text-green-100">Here's your rental summary</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-home text-5xl text-green-200 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Current Rental Info - Tenant -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">My Current Rental</h3>
        
        @if($currentLease)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-500 text-sm">Property</p>
                <p class="font-semibold">{{ $currentLease->unit->property->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Unit Number</p>
                <p class="font-semibold">{{ $currentLease->unit->unit_number ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Monthly Rent</p>
                <p class="font-bold text-2xl text-green-600">¥{{ number_format($currentLease->monthly_rent) }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Lease Period</p>
                <p class="font-semibold">{{ date('M d, Y', strtotime($currentLease->start_date)) }} - {{ date('M d, Y', strtotime($currentLease->end_date)) }}</p>
            </div>
        </div>
        @else
        <p class="text-gray-500">No active lease found.</p>
        @endif
    </div>

    <!-- Stats Cards - Tenant -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Paid</p>
                    <p class="text-2xl font-bold text-green-600">¥{{ number_format($totalPaid ?? 0) }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending Payments</p>
                    <p class="text-2xl font-bold text-yellow-600">¥{{ number_format($totalPending ?? 0) }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Open Requests</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $openRequests ?? 0 }}</p>
                </div>
                <i class="fas fa-tools text-orange-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions - Tenant -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100">
                <span>Submit Maintenance Request</span>
                <i class="fas fa-arrow-right text-blue-600"></i>
            </a>
            <a href="{{ route('payments') }}" class="flex items-center justify-between p-3 bg-green-50 rounded-xl hover:bg-green-100">
                <span>View Payment History</span>
                <i class="fas fa-arrow-right text-green-600"></i>
            </a>
            <a href="{{ route('leases.index') }}" class="flex items-center justify-between p-3 bg-purple-50 rounded-xl hover:bg-purple-100">
                <span>View My Lease</span>
                <i class="fas fa-arrow-right text-purple-600"></i>
            </a>
        </div>
    </div>

    <!-- Recent Payments - Tenant -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments ?? [] as $payment)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ $payment->payment_date }}</td>
                        <td class="px-6 py-4">¥{{ number_format($payment->amount) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">{{ ucfirst($payment->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No payments yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

@endsection

@push('scripts')
@if(Auth::user()->role === 'admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Revenue (¥)',
                data: {!! json_encode($revenueData ?? [0, 0, 0, 0, 0, 0]) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 3,
                fill: true
            }]
        }
    });
</script>
@endif
@endpush