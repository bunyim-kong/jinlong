@extends('layouts.app')

@section('title', 'Property Details')
@section('page-title', 'Property Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $property->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Property information and units</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('properties.edit', $property->id) }}" class="px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-sm hover:bg-green-100">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('properties.index') }}" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Property Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Property Name</p>
                    <p class="text-lg font-medium text-gray-800 mt-1">{{ $property->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Property Type</p>
                    <p class="text-lg font-medium text-gray-800 mt-1">{{ $property->type }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Address</p>
                    <p class="text-lg font-medium text-gray-800 mt-1">{{ $property->address }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Units</p>
                    <p class="text-lg font-medium text-gray-800 mt-1">{{ $property->total_unit }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Created At</p>
                    <p class="text-lg font-medium text-gray-800 mt-1">{{ $property->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Units List -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-4">Units in this Property</h4>
                @if($property->units->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($property->units as $unit)
                            <div class="border rounded-lg p-3 hover:shadow-sm transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">Unit #{{ $unit->unit_number }}</p>
                                        <p class="text-sm text-gray-500">¥{{ number_format($unit->rent_price) }}/month</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs {{ $unit->status == 'rented' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($unit->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">{{ Str::limit($unit->facility, 50) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-door-closed text-3xl mb-2 block"></i>
                        <p>No units added yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection