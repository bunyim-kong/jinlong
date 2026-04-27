@extends('layouts.app')

@section('title', 'Lease Management')
@section('page-title', 'Lease Management')

@section('content')

@php
    $user = Auth::user();
@endphp

<style>
.renew-btn {
    transition: all 0.3s ease;
}
.renew-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>

@if($user->role === 'admin')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Total Leases</p><p class="text-3xl font-bold">{{ $totalLeases ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-file-signature text-blue-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Active Leases</p><p class="text-3xl font-bold text-green-600">{{ $activeLeases ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Expiring Soon</p><p class="text-3xl font-bold text-yellow-600">{{ $expiringLeases ?? 0 }}</p></div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center"><i class="fas fa-hourglass-half text-yellow-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6 border hover:shadow-md transition">
        <div class="flex justify-between">
            <div><p class="text-gray-500 text-sm">Monthly Revenue</p><p class="text-3xl font-bold text-purple-600">¥{{ number_format($monthlyRevenue ?? 0) }}</p></div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-chart-line text-purple-600 text-xl"></i></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tenant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Property / Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Monthly Rent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leases ?? [] as $lease)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $lease->tenant->user->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4">{{ $lease->unit->property->name ?? 'N/A' }} - Unit #{{ $lease->unit->unit_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ date('M d, Y', strtotime($lease->start_date)) }} - {{ date('M d, Y', strtotime($lease->end_date)) }}<td>
                    <td class="px-6 py-4 font-semibold">¥{{ number_format($lease->monthly_rent) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs {{ $lease->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($lease->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-800 mr-2"><i class="fas fa-eye"></i></button>
                        <button class="text-green-600 hover:text-green-800"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No leases found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">{{ ($leases ?? [])->links() }}</div>
</div>


@elseif($user->role === 'tenant')

<div class="mx-auto">
    @if($lease)
    <!-- Current Lease Card -->
    <div class="bg-white rounded-2xl shadow-lg border-0 overflow-hidden">
        <!-- Header with gradient -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h3 class="text-xl font-bold text-white">My Current Lease</h3>
            <p class="text-blue-100 text-sm mt-1">Your active rental agreement</p>
        </div>
        
        <div class="p-6">   
            <div class="flex justify-end">
                @php
                    $endDate = \Carbon\Carbon::parse($lease->end_date);
                    $today = \Carbon\Carbon::now();
                    $daysLeft = $today->diffInDays($endDate, false);
                    $isExpiringSoon = $daysLeft <= 30 && $daysLeft > 0;
                @endphp
                
                @if($isExpiringSoon)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Expires in {{ $daysLeft }} days
                    </span>
                @else
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> Active
                    </span>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Property</p>
                        <p class="text-lg font-bold text-gray-800">{{ $lease->unit->property->name ?? 'N/A' }}</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Unit Number</p>
                        <p class="text-lg font-bold text-gray-800">#{{ $lease->unit->unit_number ?? 'N/A' }}</p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Monthly Rent</p>
                        <p class="text-2xl font-bold text-blue-600">¥{{ number_format($lease->monthly_rent) }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="border-l-4 border-orange-500 pl-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Start Date</p>
                        <p class="text-lg font-bold text-gray-800">{{ date('F d, Y', strtotime($lease->start_date)) }}</p>
                    </div>
                    <div class="border-l-4 border-red-500 pl-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold">End Date</p>
                        <p class="text-lg font-bold text-gray-800">{{ date('F d, Y', strtotime($lease->end_date)) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Unit Facilities -->
            <div class="mt-6 pt-4 border-t">
                <p class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i> Unit Facilities
                </p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $facilities = explode(',', $lease->unit->facility ?? '');
                    @endphp
                    @foreach($facilities as $facility)
                        @if(trim($facility))
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                <i class="fas fa-check text-green-500 mr-1"></i> {{ trim($facility) }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Terms Card -->
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-gavel text-gray-600 mr-2"></i> Lease Terms
            </h4>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-calendar-check text-green-500 mt-0.5 mr-3"></i>
                    <span>Rent due on the <strong>1st of each month</strong></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-clock text-green-500 mt-0.5 mr-3"></i>
                    <span><strong>5% late fee</strong> applied after the 5th</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-shield-alt text-green-500 mt-0.5 mr-3"></i>
                    <span><strong>2 months rent</strong> security deposit held</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-bell text-green-500 mt-0.5 mr-3"></i>
                    <span><strong>30-day notice</strong> required for termination</span>
                </li>
            </ul>
        </div>
        
        <!-- Renewal Card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 p-6">
            <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-handshake text-blue-600 mr-2"></i> Lease Renewal
            </h4>
            
            @php
                $endDate = \Carbon\Carbon::parse($lease->end_date);
                $canRenew = $endDate->diffInDays(now(), false) <= 60;
            @endphp
            
            @if($canRenew)
                <p class="text-sm text-gray-600 mb-4">
                    Your lease ends on <strong>{{ date('F d, Y', strtotime($lease->end_date)) }}</strong>.
                    You can request renewal now!
                </p>
                <button onclick="openRenewalModal()" 
                        class="renew-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-file-signature"></i>
                    Request Lease Renewal
                </button>
            @else
                <p class="text-sm text-gray-600 mb-4">
                    Your lease ends on <strong>{{ date('F d, Y', strtotime($lease->end_date)) }}</strong>.
                    You can request renewal within 60 days of expiration.
                </p>
                <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="fas fa-clock"></i>
                    Renewal Available Soon
                </button>
            @endif
            
            <p class="text-xs text-gray-400 mt-3 text-center">
                <i class="fas fa-info-circle mr-1"></i> Renewal requests will be reviewed by management
            </p>
        </div>
    </div>
    
    @else
    <!-- No Lease Found -->
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
        <i class="fas fa-file-contract text-5xl text-gray-300 mb-4 block"></i>
        <p class="text-gray-500 mb-2">No active lease found</p>
        <p class="text-sm text-gray-400">Please contact the property manager</p>
    </div>
    @endif
</div>

<div id="renewalModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl max-w-md w-full mx-4 overflow-hidden animate-fadeIn">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Request Lease Renewal</h3>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Select Renewal Period</label>
                <select id="renewalPeriod" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="1">1 months</option>
                    <option value="2">2 months</option>
                    <option value="3">3 months</option>
                    <option value="6">6 months</option>
                    <option value="12">12 months</option>
                    <option value="24">24 months</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button onclick="closeRenewalModal()" class="flex-1 px-4 py-2 border rounded-lg">Cancel</button>
                <button onclick="submitRenewal()" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Submit Request
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease;
}
</style>

<script>
function openRenewalModal() {
    document.getElementById('renewalModal').classList.remove('hidden');
    document.getElementById('renewalModal').classList.add('flex');
}

function closeRenewalModal() {
    document.getElementById('renewalModal').classList.add('hidden');
    document.getElementById('renewalModal').classList.remove('flex');
}

function submitRenewal() {
    const period = document.getElementById('renewalPeriod').value;
    const notes = document.getElementById('renewalNotes').value;
    
    alert(`Renewal request submitted!\n\nPeriod: ${period} months\nNotes: ${notes || 'None'}\n\nManagement will contact you soon.`);
    closeRenewalModal();
}

window.onclick = function(event) {
    const modal = document.getElementById('renewalModal');
    if (event.target === modal) {
        closeRenewalModal();
    }
}
</script>

@endif

@endsection