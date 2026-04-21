<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col sidebar-transition fixed lg:relative z-30 h-full -translate-x-full lg:translate-x-0">
    <!-- Logo Area -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-home text-white text-sm"></i>
            </div>
            <span class="font-bold text-gray-800 text-lg sidebar-text">Jinlong PM</span>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                AD
            </div>
            <div class="flex-1 sidebar-text">
                <p class="text-sm font-semibold text-gray-800">Admin User</p>
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 scrollbar-thin">
        <div class="px-4 mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">Main Menu</p>
        </div>
        
        <div class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-tachometer-alt w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Dashboard</span>
            </a>
            
            <!-- Properties -->
            <a href="{{ route('properties') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-building w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Properties</span>
            </a>
            
            <!-- Tenants -->
            <a href="{{ route('tenants.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-users w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Tenants</span>
            </a>
            
            <!-- Leases -->
            <a href="{{ route('leases') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-file-signature w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Leases</span>
            </a>
            
            <!-- Payments -->
            <a href="{{ route('payments.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-credit-card w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Payments</span>
            </a>
            
            <!-- Maintenance -->
            <a href="{{ route('maintenance.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-tools w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Maintenance</span>
            </a>
        </div>
        
        <div class="px-4 mt-8 mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">Reports</p>
        </div>
        
        <div class="space-y-1">
            <a href="{{ route('reports.payments') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Payment Reports</span>
            </a>
            <a href="{{ route('reports.occupancy') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-chart-pie w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Occupancy Rate</span>
            </a>
        </div>
    </nav>
    
    <!-- Footer Menu -->
    <div class="border-t border-gray-200 p-4">
        <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors group" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt w-5 h-5"></i>
            <span class="ml-3 sidebar-text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>