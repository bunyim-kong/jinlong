@extends('layouts.app')

@section('title', 'Dashboard - Rental Home Management')
@section('page-title', 'Dashboard')

@section('content')

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 mb-8 text-white">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold mb-2">Welcome back, Admin!</h2>
            <p class="text-blue-100">Here's what's happening with your properties today.</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-chart-line text-5xl text-blue-200 opacity-50"></i>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Properties -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-600 transition-colors duration-300">
                <i class="fas fa-building text-2xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <span class="text-3xl font-bold text-gray-800">24</span>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">Total Properties</h3>
        <div class="flex items-center text-sm">
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            <span class="text-green-600 font-medium">+3</span>
            <span class="text-gray-500 ml-1">this month</span>
        </div>
    </div>

    <!-- Active Tenants -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-100 rounded-xl group-hover:bg-green-600 transition-colors duration-300">
                <i class="fas fa-users text-2xl text-green-600 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <span class="text-3xl font-bold text-gray-800">156</span>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">Active Tenants</h3>
        <div class="flex items-center text-sm">
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            <span class="text-green-600 font-medium">+12</span>
            <span class="text-gray-500 ml-1">new this month</span>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-100 rounded-xl group-hover:bg-purple-600 transition-colors duration-300">
                <i class="fas fa-wallet text-2xl text-purple-600 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <span class="text-3xl font-bold text-gray-800">¥45,280</span>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">Monthly Revenue</h3>
        <div class="flex items-center text-sm">
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            <span class="text-green-600 font-medium">+8.2%</span>
            <span class="text-gray-500 ml-1">from last month</span>
        </div>
    </div>

    <!-- Occupancy Rate -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-orange-100 rounded-xl group-hover:bg-orange-600 transition-colors duration-300">
                <i class="fas fa-chart-pie text-2xl text-orange-600 group-hover:text-white transition-colors duration-300"></i>
            </div>
            <span class="text-3xl font-bold text-gray-800">94%</span>
        </div>
        <h3 class="text-gray-600 font-medium mb-1">Occupancy Rate</h3>
        <div class="flex items-center text-sm">
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            <span class="text-green-600 font-medium">+5%</span>
            <span class="text-gray-500 ml-1">this quarter</span>
        </div>
    </div>
</div>

<!-- Charts and Recent Activity Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Revenue Chart - Takes 2/3 of space -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
                <p class="text-sm text-gray-500 mt-1">Monthly revenue trends for 2024</p>
            </div>
            <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500 bg-white">
                <option>This Year</option>
                <option>Last Year</option>
                <option>Last 6 Months</option>
            </select>
        </div>
        <canvas id="revenueChart" height="250"></canvas>
    </div>

    <!-- Quick Actions - Takes 1/3 of space -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <button class="w-full flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-plus-circle text-blue-600"></i>
                    <span class="text-gray-700 font-medium">Add New Property</span>
                </div>
                <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition-transform"></i>
            </button>
            
            <button class="w-full flex items-center justify-between p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-colors group">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-user-plus text-green-600"></i>
                    <span class="text-gray-700 font-medium">Register New Tenant</span>
                </div>
                <i class="fas fa-arrow-right text-green-600 group-hover:translate-x-1 transition-transform"></i>
            </button>
            
            <button class="w-full flex items-center justify-between p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-file-signature text-purple-600"></i>
                    <span class="text-gray-700 font-medium">Create Lease Contract</span>
                </div>
                <i class="fas fa-arrow-right text-purple-600 group-hover:translate-x-1 transition-transform"></i>
            </button>
            
            <button class="w-full flex items-center justify-between p-3 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition-colors group">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-wrench text-yellow-600"></i>
                    <span class="text-gray-700 font-medium">Log Maintenance Request</span>
                </div>
                <i class="fas fa-arrow-right text-yellow-600 group-hover:translate-x-1 transition-transform"></i>
            </button>
            
            <button class="w-full flex items-center justify-between p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors group">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-file-invoice-dollar text-red-600"></i>
                    <span class="text-gray-700 font-medium">Record Payment</span>
                </div>
                <i class="fas fa-arrow-right text-red-600 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>
    </div>
</div>

<!-- Recent Data Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Recent Payments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
                <p class="text-sm text-gray-500 mt-1">Latest rental payments received</p>
            </div>
            <a href="#" class="text-blue-600 text-sm hover:underline">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-blue-600 text-sm font-semibold">张</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Zhang Wei</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Sunshine Tower #304</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">¥3,800</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Paid</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-green-600 text-sm font-semibold">李</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Li Na</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Riverside #102</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">¥4,200</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Paid</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-yellow-600 text-sm font-semibold">王</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Wang Fang</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Garden Heights #205</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">¥5,500</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">Pending</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-red-600 text-sm font-semibold">陈</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Chen Long</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Ocean View #501</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">¥6,800</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Overdue</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Maintenance Requests -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Maintenance Requests</h3>
                <p class="text-sm text-gray-500 mt-1">Pending and in-progress requests</p>
            </div>
            <a href="#" class="text-blue-600 text-sm hover:underline">View All →</a>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wrench text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Leaking Faucet - Kitchen</p>
                            <p class="text-sm text-gray-500 mt-1">Unit 304, Sunshine Tower</p>
                            <p class="text-xs text-gray-400 mt-1">Requested by: Zhang Wei • 2 hours ago</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">In Progress</span>
                </div>
            </div>
            
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bolt text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Electrical Issue - Power Outage</p>
                            <p class="text-sm text-gray-500 mt-1">Unit 102, Riverside Apartments</p>
                            <p class="text-xs text-gray-400 mt-1">Requested by: Li Na • 1 day ago</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">Assigned</span>
                </div>
            </div>
            
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-snowflake text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">AC Not Working - No Cooling</p>
                            <p class="text-sm text-gray-500 mt-1">Unit 205, Garden Heights</p>
                            <p class="text-xs text-gray-400 mt-1">Requested by: Wang Fang • 2 days ago</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Urgent</span>
                </div>
            </div>
            
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-toilet text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Toilet Clogged</p>
                            <p class="text-sm text-gray-500 mt-1">Unit 501, Ocean View</p>
                            <p class="text-xs text-gray-400 mt-1">Requested by: Chen Long • 3 days ago</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full font-medium">Pending</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Expiring Leases Alert -->
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            </div>
            <div>
                <p class="font-semibold text-yellow-800">⚠️ 3 Leases Expiring Soon</p>
                <p class="text-sm text-yellow-700">Leases for Units 304, 102, and 205 will expire in the next 30 days.</p>
            </div>
        </div>
        <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
            Review Renewals
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (¥)',
                data: [32000, 35000, 38000, 42000, 45280, 48000, 51000, 53000, 55000, 57000, 59000, 62000],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                borderWidth: 3,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += '¥' + context.parsed.y.toLocaleString();
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '¥' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: '#e5e7eb'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush