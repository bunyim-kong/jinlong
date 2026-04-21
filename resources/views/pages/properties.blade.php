@extends('layouts.app')

@section('title', 'Property Management - Rental Home Management')
@section('page-title', 'Property Management')

@section('content')

<!-- Header with Add Button -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manage Properties</h2>
        <p class="text-gray-600 mt-1">View, edit, and manage all your rental properties</p>
    </div>
    <button onclick="openPropertyModal()" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <i class="fas fa-plus"></i>
        <span>Add New Property</span>
    </button>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="p-3 bg-blue-100 rounded-xl">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-800" id="totalProperties">0</span>
        </div>
        <h3 class="text-gray-600 font-medium">Total Properties</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="p-3 bg-green-100 rounded-xl">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-800" id="availableProperties">0</span>
        </div>
        <h3 class="text-gray-600 font-medium">Available Properties</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="p-3 bg-yellow-100 rounded-xl">
                <i class="fas fa-users text-yellow-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-800" id="occupiedProperties">0</span>
        </div>
        <h3 class="text-gray-600 font-medium">Occupied Properties</h3>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="p-3 bg-purple-100 rounded-xl">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-gray-800" id="occupancyRate">0%</span>
        </div>
        <h3 class="text-gray-600 font-medium">Occupancy Rate</h3>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6 border border-gray-100">
    <div class="flex flex-wrap gap-4 items-center justify-between">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search by property name, address, or unit number..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
        </div>
        <div class="flex gap-3">
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="all">All Status</option>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="all">All Types</option>
                <option value="apartment">Apartment</option>
                <option value="house">House</option>
                <option value="condo">Condo</option>
                <option value="studio">Studio</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>
</div>

<!-- Properties Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Property Details</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Units</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Monthly Rent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

            <tbody id="propertiesTableBody" class="divide-y divide-gray-200">
                <!-- Properties will be loaded here dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Property Modal -->
<div id="propertyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="z-index: 1000;">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 pb-3 border-b">
            <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Add New Property</h3>
            <button onclick="closePropertyModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="propertyForm">
            <input type="hidden" id="propertyId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Property Name *</label>
                    <input type="text" id="propertyName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Number *</label>
                    <input type="text" id="unitNumber" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Property Type *</label>
                    <select id="propertyType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="condo">Condo</option>
                        <option value="studio">Studio</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="propertyStatus" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" id="address" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                    <input type="number" id="bedrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                    <input type="number" id="bathrooms" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Square Feet</label>
                    <input type="number" id="squareFeet" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Rent (¥) *</label>
                    <input type="number" id="monthlyRent" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenities (comma separated)</label>
                    <input type="text" id="amenities" placeholder="e.g., Parking, Pool, Gym, AC" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closePropertyModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">Save Property</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="z-index: 1000;">
    <div class="relative top-40 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Property</h3>
            <p class="text-sm text-gray-500 mb-4">Are you sure you want to delete this property? This action cannot be undone.</p>
            <input type="hidden" id="deletePropertyId">
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg transition-colors">Cancel</button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let properties = [];

// Sample initial data
const sampleProperties = [
    {
        id: 1,
        name: 'Sunshine Tower',
        unit_number: '304',
        type: 'apartment',
        status: 'occupied',
        address: '123 Sunshine Blvd, Downtown',
        bedrooms: 2,
        bathrooms: 2,
        square_feet: 1200,
        monthly_rent: 3800,
        description: 'Beautiful apartment with city views',
        amenities: 'Parking, Pool, Gym'
    },
    {
        id: 2,
        name: 'Riverside Apartments',
        unit_number: '102',
        type: 'apartment',
        status: 'occupied',
        address: '456 River Road, Westside',
        bedrooms: 3,
        bathrooms: 2,
        square_feet: 1500,
        monthly_rent: 4200,
        description: 'Spacious apartment near the river',
        amenities: 'Parking, Pool'
    },
    {
        id: 3,
        name: 'Garden Heights',
        unit_number: '205',
        type: 'condo',
        status: 'available',
        address: '789 Garden Ave, Northside',
        bedrooms: 2,
        bathrooms: 1,
        square_feet: 950,
        monthly_rent: 5500,
        description: 'Modern condo with garden view',
        amenities: 'Gym, AC'
    },
    {
        id: 4,
        name: 'Ocean View',
        unit_number: '501',
        type: 'apartment',
        status: 'maintenance',
        address: '321 Ocean Drive, Eastside',
        bedrooms: 3,
        bathrooms: 2,
        square_feet: 1800,
        monthly_rent: 6800,
        description: 'Luxury apartment with ocean view',
        amenities: 'Parking, Pool, Gym, AC'
    }
];

// Load properties from localStorage or use sample data
function loadProperties() {
    const stored = localStorage.getItem('properties');
    if (stored) {
        properties = JSON.parse(stored);
    } else {
        properties = sampleProperties;
        saveProperties();
    }
    updateStatistics();
    renderPropertiesTable();
}

function saveProperties() {
    localStorage.setItem('properties', JSON.stringify(properties));
}

function updateStatistics() {
    const total = properties.length;
    const available = properties.filter(p => p.status === 'available').length;
    const occupied = properties.filter(p => p.status === 'occupied').length;
    const occupancyRate = total > 0 ? Math.round((occupied / total) * 100) : 0;
    
    document.getElementById('totalProperties').textContent = total;
    document.getElementById('availableProperties').textContent = available;
    document.getElementById('occupiedProperties').textContent = occupied;
    document.getElementById('occupancyRate').textContent = `${occupancyRate}%`;
}

function renderPropertiesTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    let filtered = properties.filter(property => {
        const matchesSearch = property.name.toLowerCase().includes(searchTerm) ||
                             property.address.toLowerCase().includes(searchTerm) ||
                             property.unit_number.toLowerCase().includes(searchTerm);
        const matchesStatus = statusFilter === 'all' || property.status === statusFilter;
        const matchesType = typeFilter === 'all' || property.type === typeFilter;
        
        return matchesSearch && matchesStatus && matchesType;
    });
    
    const tbody = document.getElementById('propertiesTableBody');
    
    if (filtered.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <i class="fas fa-building text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 text-lg">No properties found</p>
                    <button onclick="openPropertyModal()" class="mt-4 text-blue-600 hover:text-blue-700">Add your first property</button>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = filtered.map(property => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-m font-semibold text-gray-900">${property.name}</p>
                        <p class="text-xs text-gray-500">Unit ${property.unit_number}</p>
                        <p class="text-xs text-gray-400 mt-1">${property.address.substring(0, 30)}${property.address.length > 30 ? '...' : ''}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="text-sm text-gray-700 capitalize">${property.type}</span>
            </td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-full text-xs font-semibold ${getStatusColor(property.status)}">
                    ${getStatusText(property.status)}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-2 text-sm text-gray-700">
                    <span><i class="fas fa-bed text-gray-400"></i> ${property.bedrooms}</span>
                    <span><i class="fas fa-bath text-gray-400"></i> ${property.bathrooms}</span>
                    <span class="text-xs text-gray-500">${property.square_feet} sqft</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-lg font-bold text-blue-600">$${property.monthly_rent.toLocaleString()}</p>
                <p class="text-xs text-gray-500">/month</p>
            </td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
    
                    <button onclick="editProperty(${property.id})" 
                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="openDeleteModal(${property.id})" 
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusColor(status) {
    switch(status) {
        case 'available': return 'bg-green-100 text-green-700';
        case 'occupied': return 'bg-blue-100 text-blue-700';
        case 'maintenance': return 'bg-red-100 text-red-700';
        default: return 'bg-gray-100 text-gray-700';
    }
}

function getStatusText(status) {
    switch(status) {
        case 'available': return 'Available';
        case 'occupied': return 'Occupied';
        case 'maintenance': return 'Maintenance';
        default: return status;
    }
}

function openPropertyModal(propertyId = null) {
    const modal = document.getElementById('propertyModal');
    const modalTitle = document.getElementById('modalTitle');
    
    if (propertyId) {
        modalTitle.textContent = 'Edit Property';
        const property = properties.find(p => p.id === propertyId);
        if (property) {
            document.getElementById('propertyId').value = property.id;
            document.getElementById('propertyName').value = property.name;
            document.getElementById('unitNumber').value = property.unit_number;
            document.getElementById('propertyType').value = property.type;
            document.getElementById('propertyStatus').value = property.status;
            document.getElementById('address').value = property.address;
            document.getElementById('bedrooms').value = property.bedrooms;
            document.getElementById('bathrooms').value = property.bathrooms;
            document.getElementById('squareFeet').value = property.square_feet;
            document.getElementById('monthlyRent').value = property.monthly_rent;
            document.getElementById('description').value = property.description || '';
            document.getElementById('amenities').value = property.amenities || '';
        }
    } else {
        modalTitle.textContent = 'Add New Property';
        document.getElementById('propertyForm').reset();
        document.getElementById('propertyId').value = '';
    }
    
    modal.classList.remove('hidden');
}

function closePropertyModal() {
    document.getElementById('propertyModal').classList.add('hidden');
    document.getElementById('propertyForm').reset();
}

function openDeleteModal(propertyId) {
    document.getElementById('deletePropertyId').value = propertyId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    const propertyId = parseInt(document.getElementById('deletePropertyId').value);
    properties = properties.filter(p => p.id !== propertyId);
    saveProperties();
    updateStatistics();
    renderPropertiesTable();
    closeDeleteModal();
}

function viewProperty(propertyId) {
    const property = properties.find(p => p.id === propertyId);
    if (property) {
        alert(`Property Details:\n\nName: ${property.name}\nUnit: ${property.unit_number}\nAddress: ${property.address}\nRent: ¥${property.monthly_rent}\nStatus: ${getStatusText(property.status)}\n\n${property.description || 'No description available'}`);
    }
}

document.getElementById('propertyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const propertyId = document.getElementById('propertyId').value;
    const propertyData = {
        id: propertyId ? parseInt(propertyId) : Date.now(),
        name: document.getElementById('propertyName').value,
        unit_number: document.getElementById('unitNumber').value,
        type: document.getElementById('propertyType').value,
        status: document.getElementById('propertyStatus').value,
        address: document.getElementById('address').value,
        bedrooms: parseInt(document.getElementById('bedrooms').value) || 0,
        bathrooms: parseInt(document.getElementById('bathrooms').value) || 0,
        square_feet: parseInt(document.getElementById('squareFeet').value) || 0,
        monthly_rent: parseInt(document.getElementById('monthlyRent').value),
        description: document.getElementById('description').value,
        amenities: document.getElementById('amenities').value
    };
    
    if (propertyId) {
        const index = properties.findIndex(p => p.id === parseInt(propertyId));
        properties[index] = propertyData;
    } else {
        properties.push(propertyData);
    }
    
    saveProperties();
    updateStatistics();
    renderPropertiesTable();
    closePropertyModal();
});

function editProperty(id) {
    openPropertyModal(id);
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('typeFilter').value = 'all';
    renderPropertiesTable();
}

// Event listeners for filters
document.getElementById('searchInput').addEventListener('input', renderPropertiesTable);
document.getElementById('statusFilter').addEventListener('change', renderPropertiesTable);
document.getElementById('typeFilter').addEventListener('change', renderPropertiesTable);

// Initialize
loadProperties();

// Close modals when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('propertyModal');
    const deleteModal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closePropertyModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>
@endpush