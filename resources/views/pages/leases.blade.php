@extends('layouts.app')

@section('title', 'Lease Management')
@section('page-title', 'Lease Management')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Leases</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLeases ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-signature text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400">
                <i class="fas fa-chart-line mr-1"></i> All time
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Leases</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeLeases ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-green-600">
                <i class="fas fa-arrow-up mr-1"></i> Currently active
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Expiring Soon</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $expiringLeases ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-yellow-600">
                <i class="fas fa-calendar mr-1"></i> Next 30 days
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Monthly Revenue</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">¥{{ number_format($monthlyRevenue ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 text-xs text-purple-600">
                <i class="fas fa-arrow-up mr-1"></i> +12% from last month
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" onkeyup="filterTable()" 
                           placeholder="Search by tenant or property..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                </div>
            </div>
            <div>
                <select id="statusFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 bg-white">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                    <option value="terminated">Terminated</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Leases Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="leasesTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leases ?? [] as $lease)
                    <tr class="hover:bg-gray-50 transition-colors lease-row" data-status="{{ $lease->status }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $lease->tenant->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $lease->tenant->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $lease->unit->property->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-lg">#{{ $lease->unit->unit_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700">{{ date('M d, Y', strtotime($lease->start_date)) }} - {{ date('M d, Y', strtotime($lease->end_date)) }}</p>
                            @php
                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($lease->end_date), false);
                            @endphp
                            @if($daysLeft > 0 && $daysLeft <= 30)
                                <p class="text-xs text-red-500 mt-1"><i class="fas fa-exclamation-triangle mr-1"></i> Expires in {{ $daysLeft }} days</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-blue-600">¥{{ number_format($lease->monthly_rent) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = $lease->status == 'active' ? 'bg-green-100 text-green-700' : ($lease->status == 'expiring' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <i class="fas {{ $lease->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ ucfirst($lease->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                <button class="w-8 h-8 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button class="w-8 h-8 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <i class="fas fa-file-contract text-5xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-500">No leases found</p>
                            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Create First Lease</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ ($leases ?? [])->links() }}
        </div>
    </div>
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.lease-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status');
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusValue === 'all' || status === statusValue;
        
        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
}
</script>
@endsection