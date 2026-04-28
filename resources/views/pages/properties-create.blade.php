@extends('layouts.app')

@section('title', 'Add New Property')
@section('page-title', 'Add New Property')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Property Information</h3>
            <p class="text-sm text-gray-500 mt-1">Fill in the details to add a new property</p>
        </div>

        <form action="{{ route('properties.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Type *</label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Condo">Condo</option>
                        <option value="House">House</option>
                        <option value="Commercial">Commercial</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                    <textarea name="address" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Units *</label>
                    <input type="number" name="total_unit" value="{{ old('total_unit') }}" required min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    @error('total_unit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('properties.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> Create Property
                </button>
            </div>
        </form>
    </div>
</div>
@endsection