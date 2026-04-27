@extends('layouts.app')

@section('title', 'Tenants Management')
@section('page-title', 'Tenants')

@section('content')

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
    max-width: 550px;
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
</style>

<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-500">Manage all tenants in the system</p>
        </div>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-user-plus mr-2"></i> Add New Tenant
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Total Tenants</p><p class="text-3xl font-bold">{{ $totalTenants ?? 0 }}</p></div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-blue-600 text-xl"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Active Leases</p><p class="text-3xl font-bold text-green-600">{{ $activeLeases ?? 0 }}</p></div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-file-signature text-green-600 text-xl"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Male</p><p class="text-3xl font-bold text-blue-600">{{ $maleCount ?? 0 }}</p></div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-mars text-blue-600 text-xl"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border">
            <div class="flex justify-between">
                <div><p class="text-gray-500 text-sm">Female</p><p class="text-3xl font-bold text-pink-600">{{ $femaleCount ?? 0 }}</p></div>
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center"><i class="fas fa-venus text-pink-600 text-xl"></i></div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" onkeyup="filterTable()" 
                           placeholder="Search by name, email or phone..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div>
                <select id="genderFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 bg-white">
                    <option value="all">All Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div>
                <select id="statusFilter" onchange="filterTable()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 bg-white">
                    <option value="all">All Status</option>
                    <option value="active">Active Lease</option>
                    <option value="inactive">No Lease</option>
                </select>
            </div>
            <div>
                <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="tenantsTableBody">
                    @forelse($tenants ?? [] as $tenant)
                    <tr class="border-t hover:bg-gray-50 tenant-row" 
                        data-gender="{{ $tenant->sex }}"
                        data-status="{{ $tenant->leases->where('status', 'active')->count() > 0 ? 'active' : 'inactive' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ $tenant->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $tenant->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $tenant->phone_number }}</td>
                        <td class="px-6 py-4 capitalize {{ $tenant->sex == 'male' ? 'text-blue-600' : 'text-pink-600' }}">
                            {{ $tenant->sex }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $hasActiveLease = $tenant->leases->where('status', 'active')->count() > 0;
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $hasActiveLease ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $hasActiveLease ? 'Active Lease' : 'No Lease' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="deleteTenant({{ $tenant->id }})" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-3 block"></i>
                            <p>No tenants found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ ($tenants ?? [])->links() }}
        </div>
    </div>
</div>

<!-- Add Tenant Modal -->
<div id="addTenantModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-lg font-bold">Add New Tenant</h3>
            <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('tenants.store') }}" method="POST">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Full Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password *</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Gender *</label>
                        <select name="sex" required class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date of Birth *</label>
                        <input type="date" name="dob" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone Number *</label>
                    <input type="text" name="phone_number" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address *</label>
                    <textarea name="address" rows="2" required class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Add Tenant
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functions
function openAddModal() {
    document.getElementById('addTenantModal').style.display = 'flex';
}
function closeAddModal() {
    document.getElementById('addTenantModal').style.display = 'none';
}

// Filter functions
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const genderValue = document.getElementById('genderFilter').value;
    const statusValue = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.tenant-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const gender = row.getAttribute('data-gender');
        const status = row.getAttribute('data-status');
        const matchesSearch = text.includes(searchValue);
        const matchesGender = genderValue === 'all' || gender === genderValue;
        const matchesStatus = statusValue === 'all' || status === statusValue;
        
        row.style.display = matchesSearch && matchesGender && matchesStatus ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('genderFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    filterTable();
}

// Delete function
function deleteTenant(id) {
    if (confirm('Are you sure you want to delete this tenant?')) {
        fetch('/tenants/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addTenantModal');
    if (event.target == modal) {
        closeAddModal();
    }
}
</script>

@endsection