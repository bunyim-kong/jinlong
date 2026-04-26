@extends('layouts.app')

@section('title', 'Maintenance Management')
@section('page-title', 'Maintenance Requests')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalRequests ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $pendingRequests ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">In Progress</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $inProgressRequests ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tools text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $completedRequests ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" onkeyup="filterTable()" 
                           placeholder="Search by tenant, title or unit..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                </div>
            </div>
            <div>
                <select id="statusFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-500 bg-white">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <select id="priorityFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-500 bg-white">
                    <option value="all">All Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Maintenance Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="maintenanceTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Requested By</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Property/Unit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Requested</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($maintenanceRequests ?? [] as $request)
                    <tr class="hover:bg-gray-50 transition-colors maintenance-row" data-status="{{ $request->status }}" data-priority="{{ $request->priority }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $request->tenant->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $request->tenant->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $request->title }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($request->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $request->unit->property->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">Unit #{{ $request->unit->unit_number ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityClass = $request->priority == 'high' ? 'bg-red-100 text-red-700' : ($request->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700');
                                $priorityIcon = $request->priority == 'high' ? 'fa-arrow-up' : ($request->priority == 'medium' ? 'fa-minus' : 'fa-arrow-down');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $priorityClass }}">
                                <i class="fas {{ $priorityIcon }} mr-1"></i>
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = $request->status == 'completed' ? 'bg-green-100 text-green-700' : ($request->status == 'in_progress' ? 'bg-blue-100 text-blue-700' : ($request->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'));
                                $statusIcon = $request->status == 'completed' ? 'fa-check-circle' : ($request->status == 'in_progress' ? 'fa-spinner fa-pulse' : 'fa-clock');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }} mr-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ date('M d, Y', strtotime($request->request_date)) }}</span>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->request_date)->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                <button class="w-8 h-8 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @if($request->status != 'completed')
                                <button class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <i class="fas fa-tools text-5xl text-gray-300 mb-4 block"></i>
                            <p class="text-gray-500">No maintenance requests found</p>
                            <button class="mt-4 px-4 py-2 bg-orange-600 text-white rounded-lg text-sm">Create New Request</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
            <p class="text-sm text-gray-500">Showing {{ $maintenanceRequests->count() ?? 0 }} of {{ $maintenanceRequests->total() ?? 0 }} requests</p>
            <div>
                {{ ($maintenanceRequests ?? [])->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const priorityValue = document.getElementById('priorityFilter').value;
    const rows = document.querySelectorAll('.maintenance-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const status = row.getAttribute('data-status');
        const priority = row.getAttribute('data-priority');
        const matchesSearch = text.includes(searchValue);
        const matchesStatus = statusValue === 'all' || status === statusValue;
        const matchesPriority = priorityValue === 'all' || priority === priorityValue;
        
        row.style.display = matchesSearch && matchesStatus && matchesPriority ? '' : 'none';
    });
}
</script>
@endsection