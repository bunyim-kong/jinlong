<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col sidebar-transition fixed lg:relative z-30 h-full -translate-x-full lg:translate-x-0">
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                <i class="fas fa-home text-white text-sm"></i>
            </div>
            
            <div class="flex flex-col sidebar-text">
                <span class="font-bold text-gray-800 text-lg leading-tight">Jinlong</span>
                <span class="text-xs text-gray-500">Property Management</span>
            </div>
        </div>

        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                {{ substr(Auth::user()->name ?? 'AD', 0, 2) }}
            </div>
            <div class="flex-1 sidebar-text">
                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin User' }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role ?? 'Administrator') }}</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4 scrollbar-thin">
        <div class="px-4 mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">Main Menu</p>
        </div>
        
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-tachometer-alt w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Dashboard</span>
            </a>
            
            <a href="{{ route('properties') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-building w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Properties</span>
            </a>
            
            <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-users w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Tenants</span>
            </a>
            
            <a href="{{ route('leases.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-file-signature w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Leases</span>
            </a>
            
            <a href="{{ route('payments') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-credit-card w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Payments</span>
            </a>
            
            <a href="{{ route('maintenance.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-tools w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Maintenance</span>
            </a>
        </div>
        
        <div class="px-4 mt-8 mb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider sidebar-text">Reports</p>
        </div>
        
        <div class="space-y-1">
            <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Payment Reports</span>
            </a>
            <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors group rounded-lg mx-2">
                <i class="fas fa-chart-pie w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Occupancy Rate</span>
            </a>
        </div>
    </nav>
    
    <!-- Logout Button -->
    <div class="border-t border-gray-200 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors group">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>