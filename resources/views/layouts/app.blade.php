<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rental Home Management System') | Jinlong Property</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .main-content {
            transition: margin-left 0.3s ease;
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        @include('components.sidebar')
        
        <div class="flex-1 flex flex-col overflow-hidden main-content">
            @include('components.navbar')
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 scrollbar-thin">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs))
                    <div class="mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            @foreach($breadcrumbs as $crumb)
                                @if(!$loop->last)
                                    <a href="{{ $crumb['url'] }}" class="hover:text-blue-600">{{ $crumb['label'] }}</a>
                                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                                @else
                                    <span class="text-gray-900 font-medium">{{ $crumb['label'] }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Page Header -->
                @hasSection('page-header')
                    <div class="mb-6">
                        @yield('page-header')
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden sidebar-transition"></div>
    
    <script>
        // mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            if (window.innerWidth < 1024) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            } else {
                sidebar.classList.toggle('w-20');
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.classList.toggle('hidden');
                });
                document.querySelectorAll('.sidebar-icon-only').forEach(el => {
                    el.classList.toggle('hidden');
                });
            }
        }
        
        // mobile sidear when closing
        document.getElementById('mobile-overlay')?.addEventListener('click', function() {
            document.getElementById('sidebar')?.classList.add('-translate-x-full');
            this.classList.add('hidden');
        });
    
    </script>
    
    @stack('scripts')
</body>
</html>