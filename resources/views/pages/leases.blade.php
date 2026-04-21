@extends('layouts.app')

@section('title', 'My Lease Agreement')
@section('page-title', 'My Lease Agreement')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Current Lease Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-file-signature text-blue-600 mr-2"></i>
                Current Lease Contract
            </h3>
        </div>
        
        @if($lease)
        <div class="p-6">
            <!-- Lease Status Badge -->
            <div class="flex justify-end mb-4">
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle mr-1"></i> Active
                </span>
            </div>
            
            <!-- Lease Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">Property</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $lease->unit->property->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">Unit Number</label>
                        <p class="text-gray-900 font-medium mt-1">{{ $lease->unit->unit_number ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">Monthly Rent</label>
                        <p class="text-2xl font-bold text-blue-600 mt-1">¥{{ number_format($lease->monthly_rent) }}</p>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">Start Date</label>
                        <p class="text-gray-900 font-medium mt-1">{{ date('F d, Y', strtotime($lease->start_date)) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">End Date</label>
                        <p class="text-gray-900 font-medium mt-1">{{ date('F d, Y', strtotime($lease->end_date)) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-xs text-gray-500 uppercase font-semibold">Lease Duration</label>
                        <p class="text-gray-900 font-medium mt-1">
                            @php
                                $start = new DateTime($lease->start_date);
                                $end = new DateTime($lease->end_date);
                                $diff = $start->diff($end);
                                echo $diff->m . ' months ' . $diff->d . ' days';
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Unit Facilities -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <label class="text-xs text-gray-500 uppercase font-semibold">Unit Facilities</label>
                <div class="mt-2 flex flex-wrap gap-2">
                    @php
                        $facilities = explode(',', $lease->unit->facility ?? '');
                    @endphp
                    @foreach($facilities as $facility)
                        @if(trim($facility))
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i> {{ trim($facility) }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="p-12 text-center">
            <i class="fas fa-file-contract text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">No active lease agreement found.</p>
            <p class="text-sm text-gray-400 mt-2">Please contact the administrator.</p>
        </div>
        @endif
    </div>
    
    <!-- Lease Terms Card -->
    @if($lease)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-gavel text-gray-600 mr-2"></i>
                Lease Terms & Conditions
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4 text-gray-600 text-sm">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-calendar-check text-green-500 mt-0.5"></i>
                    <p>Lease agreement is valid from <strong>{{ date('M d, Y', strtotime($lease->start_date)) }}</strong> to <strong>{{ date('M d, Y', strtotime($lease->end_date)) }}</strong></p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-money-bill-wave text-green-500 mt-0.5"></i>
                    <p>Rent of <strong>¥{{ number_format($lease->monthly_rent) }}</strong> is due on the 1st of each month</p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-clock text-green-500 mt-0.5"></i>
                    <p>Late payment fee of 5% will be charged after the 5th of each month</p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-shield-alt text-green-500 mt-0.5"></i>
                    <p>Security deposit of 2 months rent is held for the duration of the lease</p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-bell text-green-500 mt-0.5"></i>
                    <p>30-day written notice required for lease termination or non-renewal</p>
                </div>
            </div>
            
            <!-- Download Button -->
            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <button class="px-6 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                    <i class="fas fa-download mr-2"></i> Download Lease PDF
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection