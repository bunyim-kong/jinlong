@extends('layouts.app')

@section('title', 'Maintenance Management')
@section('page-title', 'Maintenance Requests')

@section('content')

@php
    $user = Auth::user();
@endphp

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: white;
    border-radius: 1rem;
    max-width: 500px;
    width: 90%;
    max-height: 85vh;
    overflow-y: auto;
    animation: modalFadeIn 0.3s ease;
}
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}
.modal-header {
    position: sticky;
    top: 0;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    position: sticky;
    bottom: 0;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}
.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}
.btn-approve {
    background: #dbeafe;
    color: #1e40af;
}
.btn-approve:hover {
    background: #bfdbfe;
    transform: translateY(-1px);
}
.btn-complete {
    background: #d1fae5;
    color: #065f46;
}
.btn-complete:hover {
    background: #a7f3d0;
    transform: translateY(-1px);
}
.btn-cancel {
    background: #fee2e2;
    color: #991b1b;
}
.btn-cancel:hover {
    background: #fecaca;
    transform: translateY(-1px);
}
.btn-view {
    background: #f3f4f6;
    color: #374151;
}
.btn-view:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}
.status-pending { background: #fef3c7; color: #d97706; }
.status-in_progress { background: #dbeafe; color: #2563eb; }
.status-completed { background: #d1fae5; color: #059669; }
.status-cancelled { background: #fee2e2; color: #dc2626; }
.priority-high { background: #fee2e2; color: #dc2626; }
.priority-medium { background: #fef3c7; color: #d97706; }
.priority-low { background: #d1fae5; color: #059669; }

.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}
.toast-success { background: #10b981; }
.toast-error { background: #ef4444; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

{{-- ========== ADMIN VIEW ========== --}}
@if($user->role === 'admin')

<!-- Search and Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" 
                       placeholder="Search by tenant, title or unit..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-500">
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
        <div>
            <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Admin Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Total Requests</p><p class="text-3xl font-bold total-requests" id="totalRequests">{{ $totalRequests ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-clipboard-list text-blue-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Pending</p><p class="text-3xl font-bold text-yellow-600 pending-count" id="pendingCount">{{ $pendingRequests ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center"><i class="fas fa-clock text-yellow-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">In Progress</p><p class="text-3xl font-bold text-blue-600 progress-count" id="progressCount">{{ $inProgressRequests ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-tools text-blue-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Completed</p><p class="text-3xl font-bold text-green-600 completed-count" id="completedCount">{{ $completedRequests ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
        </div>
    </div>
</div>

<!-- Admin Maintenance Table -->
<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-s font-medium text-gray-500">Tenant</th>
                    <th class="px-6 py-3 text-left text-s font-medium text-gray-500">Title</th>
                    <th class="px-6 py-3 text-left text-s font-medium text-gray-500">Property / Unit</th>
                    <th class="px-6 py-3 text-left text-s font-medium text-gray-500">Priority</th>
                    <th class="px-6 py-3 text-left text-s font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left text-s font-medium text-gray-500">Date</th>
                    <th class="px-4 py-3 text-left text-s font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody id="maintenanceTableBody">
                @forelse($maintenanceRequests ?? [] as $request)
                <tr class="border-t hover:bg-gray-50 maintenance-row" 
                    data-status="{{ $request->status }}" 
                    data-priority="{{ $request->priority }}"
                    data-id="{{ $request->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">{{ $request->tenant->user->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $request->title }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $request->unit->property->name ?? 'N/A' }} - Unit #{{ $request->unit->unit_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs priority-{{ $request->priority }}">
                            {{ ucfirst($request->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 status-cell">
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($request->status == 'pending') status-pending
                            @elseif($request->status == 'in_progress') status-in_progress
                            @elseif($request->status == 'completed') status-completed
                            @elseif($request->status == 'cancelled') status-cancelled
                            @endif">
                            @if($request->status == 'in_progress')
                                In Progress
                            @else
                                {{ ucfirst($request->status) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-4 py-4 text-gray-500">{{ date('M d, Y', strtotime($request->request_date)) }}</td>
                    <td class="px py-4 actions-cell">
                        <div class="flex gap-2">
                            @if($request->status == 'pending')
                                <button onclick="event.stopPropagation(); updateStatus({{ $request->id }}, 'approve')" class="btn-action btn-approve">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                            @endif
                            @if($request->status == 'in_progress')
                                <button onclick="event.stopPropagation(); updateStatus({{ $request->id }}, 'complete')" class="btn-action btn-complete">
                                    <i class="fas fa-check-double mr-1"></i> Complete
                                </button>
                            @endif
                            @if($request->status == 'pending' || $request->status == 'in_progress')
                                <button onclick="event.stopPropagation(); updateStatus({{ $request->id }}, 'cancel')" class="btn-action btn-cancel">
                                    <i class="fas fa-times mr-1"></i> Cancel
                                </button>
                            @endif
                        </div>
                    </td>
                 </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-tools text-4xl mb-3 block"></i>
                        No maintenance requests found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">{{ ($maintenanceRequests ?? [])->links() }}</div>
</div>


{{-- ========== TENANT VIEW ========== --}}
@elseif($user->role === 'tenant')

<div class="flex justify-end mb-4">
    <button onclick="openRequestModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i> New Request
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Pending</p><p class="text-2xl font-bold text-yellow-600">{{ $pendingCount ?? 0 }}</p></div>
            <i class="fas fa-clock text-yellow-500 text-3xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">In Progress</p><p class="text-2xl font-bold text-blue-600">{{ $inProgressCount ?? 0 }}</p></div>
            <i class="fas fa-tools text-blue-500 text-3xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Completed</p><p class="text-2xl font-bold text-green-600">{{ $completedCount ?? 0 }}</p></div>
            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
        </div>
    </div>
</div>

<div class="space-y-4">
    @forelse($maintenanceRequests ?? [] as $request)
    <div class="bg-white rounded-2xl shadow-sm border p-5 hover:shadow-md transition cursor-pointer" onclick="showDetails({{ $request->id }})">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="font-bold text-lg">{{ $request->title }}</h3>
                    <span class="px-2 py-1 rounded-full text-xs priority-{{ $request->priority }}">
                        {{ ucfirst($request->priority) }}
                    </span>
                </div>
                <p class="text-gray-600 text-sm mb-2">{{ Str::limit($request->description, 100) }}</p>
                <div class="flex gap-4 text-sm text-gray-400">
                    <span><i class="fas fa-building mr-1"></i> {{ $request->unit->property->name ?? 'N/A' }}</span>
                    <span><i class="fas fa-door-open mr-1"></i> Unit #{{ $request->unit->unit_number ?? 'N/A' }}</span>
                    <span><i class="fas fa-calendar mr-1"></i> {{ date('M d, Y', strtotime($request->request_date)) }}</span>
                </div>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold status-{{ $request->status }}">
                    <i class="fas {{ $request->status == 'completed' ? 'fa-check-circle' : ($request->status == 'in_progress' ? 'fa-spinner fa-pulse' : 'fa-clock') }} mr-1"></i>
                    @if($request->status == 'in_progress')
                        In Progress
                    @else
                        {{ ucfirst($request->status) }}
                    @endif
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center text-gray-500">
        <i class="fas fa-tools text-5xl text-gray-300 mb-4 block"></i>
        <p>No maintenance requests yet</p>
        <button onclick="openRequestModal()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg">Submit Your First Request</button>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ ($maintenanceRequests ?? [])->links() }}</div>

@endif

<!-- Request Modal -->
<div id="requestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">Submit Maintenance Request</h3>
            <button onclick="closeRequestModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('maintenance.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Title *</label>
                    <input type="text" name="title" placeholder="e.g., Leaking Faucet" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description *</label>
                    <textarea name="description" rows="4" placeholder="Please describe the issue in detail..." required 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Unit *</label>
                    <select name="unit_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Unit</option>
                        @foreach($units ?? [] as $unit)
                            <option value="{{ $unit->id }}">Unit #{{ $unit->unit_number }} - {{ $unit->property->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Priority *</label>
                    <select name="priority" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="low">Low - Not urgent</option>
                        <option value="medium">Medium - Can wait a few days</option>
                        <option value="high">High - Need attention soon</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeRequestModal()" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">Request Details</h3>
            <button onclick="closeDetailsModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="modal-body" id="detailsBody">
            Loading...
        </div>
        <div class="modal-footer">
            <button onclick="closeDetailsModal()" class="px-4 py-2 border rounded-lg">Close</button>
        </div>
    </div>
</div>

<script>
// ========== FILTER FUNCTIONS ==========
function filterTable() {
    const searchValue = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusValue = document.getElementById('statusFilter')?.value || 'all';
    const priorityValue = document.getElementById('priorityFilter')?.value || 'all';
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

function resetFilters() {
    if (document.getElementById('searchInput')) document.getElementById('searchInput').value = '';
    if (document.getElementById('statusFilter')) document.getElementById('statusFilter').value = 'all';
    if (document.getElementById('priorityFilter')) document.getElementById('priorityFilter').value = 'all';
    filterTable();
}

// ========== MODAL FUNCTIONS ==========
function openRequestModal() {
    document.getElementById('requestModal').style.display = 'flex';
}

function closeRequestModal() {
    document.getElementById('requestModal').style.display = 'none';
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

function showDetails(id) {
    const modal = document.getElementById('detailsModal');
    const body = document.getElementById('detailsBody');
    
    modal.style.display = 'flex';
    body.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-pulse text-2xl"></i> Loading...</div>';
    
    fetch('/maintenance/' + id + '/json')
        .then(response => response.json())
        .then(data => {
            body.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xl font-bold mb-1">${data.title}</h4>
                    </div>
                    <div class="border-t pt-3">
                        <p class="text-gray-600 text-sm mb-2"><strong>Description:</strong></p>
                        <p class="text-gray-700">${data.description}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><p class="text-gray-500">Requested By</p><p class="font-medium">${data.tenant?.user?.name || 'Unknown'}</p></div>
                        <div><p class="text-gray-500">Contact Email</p><p class="font-medium">${data.tenant?.email || 'N/A'}</p></div>
                        <div><p class="text-gray-500">Property / Unit</p><p class="font-medium">${data.unit?.property?.name || 'N/A'} - Unit #${data.unit?.unit_number || 'N/A'}</p></div>
                        <div><p class="text-gray-500">Priority</p><p class="priority-${data.priority} inline-block px-2 py-0.5 rounded-full text-xs">${data.priority.charAt(0).toUpperCase() + data.priority.slice(1)}</p></div>
                        <div><p class="text-gray-500">Request Date</p><p class="font-medium">${new Date(data.request_date).toLocaleDateString()}</p></div>
                        ${data.completed_date ? `<div><p class="text-gray-500">Completed Date</p><p class="font-medium">${new Date(data.completed_date).toLocaleDateString()}</p></div>` : ''}
                    </div>
                </div>
            `;
        })
        .catch(error => {
            body.innerHTML = '<div class="text-center py-8 text-red-500">Error loading details</div>';
        });
}

// ========== SHOW TOAST ==========
function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3000);
}

// ========== UPDATE STATS ==========
function updateStats() {
    fetch('/maintenance/stats')
        .then(response => response.json())
        .then(data => {
            const totalEl = document.getElementById('totalRequests');
            const pendingEl = document.getElementById('pendingCount');
            const progressEl = document.getElementById('progressCount');
            const completedEl = document.getElementById('completedCount');
            
            if (totalEl) totalEl.textContent = data.total;
            if (pendingEl) pendingEl.textContent = data.pending;
            if (progressEl) progressEl.textContent = data.in_progress;
            if (completedEl) completedEl.textContent = data.completed;
        })
        .catch(error => console.log('Error updating stats:', error));
}

// ========== UPDATE STATUS (WORKING VERSION) ==========
function updateStatus(id, action) {
    let url = '';
    let successMessage = '';
    let newStatusClass = '';
    let newStatusText = '';
    
    if (action === 'approve') {
        url = '/maintenance/' + id + '/approve';
        successMessage = 'Request approved! Status changed to In Progress.';
        newStatusClass = 'status-in_progress';
        newStatusText = 'In Progress';
    } else if (action === 'complete') {
        url = '/maintenance/' + id + '/complete';
        successMessage = 'Request marked as completed!';
        newStatusClass = 'status-completed';
        newStatusText = 'Completed';
    } else if (action === 'cancel') {
        url = '/maintenance/' + id + '/cancel';
        successMessage = 'Request has been cancelled.';
        newStatusClass = 'status-cancelled';
        newStatusText = 'Cancelled';
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Find the row by data-id
            const targetRow = document.querySelector(`.maintenance-row[data-id="${id}"]`);
            
            if (targetRow) {
                // Update status
                const statusCell = targetRow.querySelector('.status-cell');
                if (statusCell) {
                    statusCell.innerHTML = `<span class="px-2 py-1 rounded-full text-xs ${newStatusClass}">${newStatusText}</span>`;
                }
                targetRow.setAttribute('data-status', newStatusText.toLowerCase().replace(' ', '_'));
                
                // Update actions
                const actionsCell = targetRow.querySelector('.actions-cell');
                if (actionsCell && action !== 'approve') {
                    if (action === 'complete') {
                        actionsCell.innerHTML = `
                            <div class="flex gap-2">
                                <button onclick="event.stopPropagation(); showDetails(${id})" class="btn-action btn-view">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Completed
                                </span>
                            </div>
                        `;
                    } else if (action === 'cancel') {
                        actionsCell.innerHTML = `
                            <div class="flex gap-2">
                                <button onclick="event.stopPropagation(); showDetails(${id})" class="btn-action btn-view">
                                    <i class="fas fa-eye mr-1"></i> View
                                </button>
                                <span class="text-red-600 text-sm font-medium">
                                    <i class="fas fa-times-circle mr-1"></i> Cancelled
                                </span>
                            </div>
                        `;
                    }
                } else if (actionsCell && action === 'approve') {
                    actionsCell.innerHTML = `
                        <div class="flex gap-2">
                            <button onclick="event.stopPropagation(); showDetails(${id})" class="btn-action btn-view">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                            <button onclick="event.stopPropagation(); updateStatus(${id}, 'complete')" class="btn-action btn-complete">
                                <i class="fas fa-check-double mr-1"></i> Complete
                            </button>
                            <button onclick="event.stopPropagation(); updateStatus(${id}, 'cancel')" class="btn-action btn-cancel">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        </div>
                    `;
                }
            }
            
            showToast(successMessage, 'success');
            updateStats();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating status. Please try again.', 'error');
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const requestModal = document.getElementById('requestModal');
    const detailsModal = document.getElementById('detailsModal');
    if (event.target == requestModal) closeRequestModal();
    if (event.target == detailsModal) closeDetailsModal();
}
</script>

@endsection