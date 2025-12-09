<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ config('app.name', 'Bags & Tea') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Alpine.js is included with Livewire -->

    <!-- Admin specific styles -->
    <style>
        .admin-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .admin-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        @livewire('admin.dashboard.sidebar')

        <!-- Main content area with proper left margin for sidebar -->
        <div class="lg:ml-64 min-h-screen">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <!-- Mobile menu button -->
                        <button
                            onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
                            class="lg:hidden p-2 -ml-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <!-- Top right actions -->
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- Current user display - hidden on small screens -->
                        <div class="hidden sm:flex items-center text-sm text-gray-700">
                            <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-4 sm:py-6">
                    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                            <!-- Flash messages -->
                            @if(session('success'))
                                <div class="mb-4 rounded-md bg-green-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="mb-4 rounded-md bg-red-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        <!-- Page content -->
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Additional Scripts -->
    @stack('scripts')

    <!-- Admin specific scripts -->
    <script>
        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Confirm logout
        window.confirmLogout = function() {
            return confirm('Are you sure you want to logout?');
        };
    </script>
</body>
</html>