@extends('layouts.app')

@section('title', 'Properties Management')
@section('page-title', 'Properties')

@section('content')

@php
    $user = Auth::user();
@endphp

<style>
.property-card {
    transition: all 0.3s ease;
}
.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}
</style>

@if($user->role === 'admin')

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-gray-500">Manage all properties and buildings</p>
    </div>
    <a href="{{ route('properties.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i> Add New Property
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Total Properties</p><p class="text-3xl font-bold">{{ $totalProperties ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-building text-blue-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Total Units</p><p class="text-3xl font-bold">{{ $totalUnits ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-door-open text-green-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Occupied Units</p><p class="text-3xl font-bold">{{ $occupiedUnits ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-purple-600 text-xl"></i></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="filterProperties()" 
                       placeholder="Search by name, type or address..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
            </div>
        </div>
        <div>
            <select id="typeFilter" onchange="filterProperties()" 
                    class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 bg-white">
                <option value="all">All Types</option>
                <option value="Apartment">Apartment</option>
                <option value="Condo">Condo</option>
                <option value="House">House</option>
                <option value="Commercial">Commercial</option>
            </select>
        </div>
        <div>
            <button onclick="resetFilters()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                <i class="fas fa-redo mr-2"></i> Reset
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="propertiesGrid">
    @forelse($properties ?? [] as $property)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden property-card" 
         data-name="{{ strtolower($property->name) }}" 
         data-type="{{ $property->type }}" 
         data-address="{{ strtolower($property->address) }}">
        
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 text-white">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-lg">{{ $property->name }}</h3>
                <span class="px-2 py-1 bg-white/20 rounded-full text-xs">{{ $property->type }}</span>
            </div>
        </div>
        
        <!-- Card Body -->
        <div class="p-4">
            <div class="flex items-start gap-2 mb-3">
                <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                <p class="text-sm text-gray-600">{{ $property->address }}</p>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-door-open text-blue-500"></i>
                    <span class="text-sm text-gray-600">{{ $property->total_unit }} Total Units</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-building text-green-500"></i>
                    <span class="text-sm text-gray-600">{{ $property->units->where('status', 'rented')->count() }} Occupied</span>
                </div>
            </div>
            
            <!-- Units Preview -->
            @if($property->units->count() > 0)
            <div class="border-t pt-3 mb-3">
                <p class="text-xs text-gray-400 mb-2">Recent Units</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($property->units->take(3) as $unit)
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">#{{ $unit->unit_number }}</span>
                    @endforeach
                    @if($property->units->count() > 3)
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">+{{ $property->units->count() - 3 }} more</span>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2 border-t">
                <button onclick="viewProperty({{ $property->id }})" class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm hover:bg-blue-100 transition">
                    <i class="fas fa-eye mr-1"></i> View
                </button>
                <button onclick="editProperty({{ $property->id }})" class="flex-1 px-3 py-2 bg-green-50 text-green-600 rounded-lg text-sm hover:bg-green-100 transition">
                    <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button onclick="deleteProperty({{ $property->id }})" class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-sm hover:bg-red-100 transition">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center text-gray-500">
        <i class="fas fa-building text-5xl text-gray-300 mb-4 block"></i>
        <p>No properties found</p>
        <a href="{{ route('properties.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg">Add Your First Property</a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if(method_exists($properties, 'links'))
<div class="mt-6">
    {{ $properties->links() }}
</div>
@endif

<script>
function filterProperties() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const typeValue = document.getElementById('typeFilter').value;
    const cards = document.querySelectorAll('.property-card');
    
    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const address = card.getAttribute('data-address');
        const type = card.getAttribute('data-type');
        const matchesSearch = name.includes(searchValue) || address.includes(searchValue);
        const matchesType = typeValue === 'all' || type === typeValue;
        
        card.style.display = matchesSearch && matchesType ? '' : 'none';
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = 'all';
    filterProperties();
}

function viewProperty(id) {
    window.location.href = '/properties/' + id;
}

function editProperty(id) {
    window.location.href = '/properties/' + id + '/edit';
}

function deleteProperty(id) {
    if (confirm('Are you sure you want to delete this property?')) {
        fetch('/properties/' + id, {
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
</script>


@elseif($user->role === 'tenant')

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInputTenant" onkeyup="filterPropertiesTenant()" 
                           placeholder="Search by name, type or address..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <div>
                <select id="typeFilterTenant" onchange="filterPropertiesTenant()" 
                        class="px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 bg-white">
                    <option value="all">All Types</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Condo">Condo</option>
                    <option value="House">House</option>
                    <option value="Commercial">Commercial</option>
                </select>
            </div>
            <div>
                <button onclick="resetFiltersTenant()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Properties Grid for Tenant -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="propertiesGridTenant">
        @forelse($properties ?? [] as $property)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden property-card-tenant hover:shadow-lg transition-all cursor-pointer"
             data-name="{{ strtolower($property->name) }}" 
             data-type="{{ $property->type }}" 
             data-address="{{ strtolower($property->address) }}"
             onclick="viewProperty({{ $property->id }})">
            
            <div class="h-32 bg-gradient-to-r from-blue-400 to-blue-600 relative">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent p-3">
                    <h3 class="text-white font-bold text-lg">{{ $property->name }}</h3>
                </div>
            </div>
            
            <div class="p-4">
                <div class="flex items-start gap-2 mb-2">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1 text-sm"></i>
                    <p class="text-sm text-gray-600">{{ $property->address }}</p>
                </div>
                
                <div class="flex justify-between items-center mt-3 pt-3 border-t">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-door-open text-blue-500"></i>
                        <span class="text-sm text-gray-600">{{ $property->total_unit }} Units</span>
                    </div>
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $property->type }}</span>
                </div>
                
                <!-- Available Units Count -->
                <div class="mt-2">
                    @php
                        $availableUnits = $property->units->where('status', 'available')->count();
                    @endphp
                    @if($availableUnits > 0)
                        <span class="text-xs text-green-600">
                            <i class="fas fa-check-circle mr-1"></i> {{ $availableUnits }} units available
                        </span>
                    @else
                        <span class="text-xs text-red-400">
                            <i class="fas fa-times-circle mr-1"></i> No units available
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center text-gray-500">
            <i class="fas fa-building text-5xl text-gray-300 mb-4 block"></i>
            <p>No properties found</p>
        </div>
        @endforelse
    </div>

    @if(method_exists($properties, 'links'))
    <div class="mt-6">
        {{ $properties->links() }}
    </div>
    @endif
</div>

<script>
function filterPropertiesTenant() {
    const searchValue = document.getElementById('searchInputTenant').value.toLowerCase();
    const typeValue = document.getElementById('typeFilterTenant').value;
    const cards = document.querySelectorAll('.property-card-tenant');
    
    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const address = card.getAttribute('data-address');
        const type = card.getAttribute('data-type');
        const matchesSearch = name.includes(searchValue) || address.includes(searchValue);
        const matchesType = typeValue === 'all' || type === typeValue;
        
        card.style.display = matchesSearch && matchesType ? '' : 'none';
    });
}

function resetFiltersTenant() {
    document.getElementById('searchInputTenant').value = '';
    document.getElementById('typeFilterTenant').value = 'all';
    filterPropertiesTenant();
}

function viewProperty(id) {
    window.location.href = '/properties/' + id;
}
</script>

@endif

@endsection