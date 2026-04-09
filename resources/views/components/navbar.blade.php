<header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6">
    <!-- Left Section -->
    <div class="flex items-center space-x-4">
        <button onclick="toggleSidebar()" class="text-gray-600 hover:text-blue-600 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <h1 class="text-xl font-semibold text-gray-800 hidden md:block">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>
    
    <!-- Right Section -->
    <div class="flex items-center space-x-4">
        <!-- Search -->
        <div class="hidden md:block relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 w-64">
        </div>
        
        <!-- Notifications -->
        <div class="relative">
            <button class="text-gray-600 hover:text-blue-600 transition-colors relative">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
            </button>
        </div>
        
        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none hover:bg-gray-50 rounded-lg px-2 py-1 transition-colors">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    AD
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-700">Admin User</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <i class="fas fa-chevron-down text-gray-500 text-xs hidden md:block"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-20" style="display: none;">
                <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-user w-4 h-4 mr-3"></i> Profile
                </a>
                <a href="{{ route('settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-cog w-4 h-4 mr-3"></i> Settings
                </a>
                <hr class="my-2">
                <a href="#" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Include Alpine.js for dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>