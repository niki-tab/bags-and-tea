<div>
    <!-- Mobile menu button -->
    <button 
        wire:click="toggleSidebar"
        class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-slate-800 text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-white shadow-lg"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Mobile overlay -->
    <div 
        x-show="@entangle('sidebarOpen')" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30"
        wire:click="closeSidebar"
    ></div>

    <!-- Sidebar -->
    <aside 
        class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 h-screen sidebar-scroll"
        :class="{'translate-x-0': @entangle('sidebarOpen'), '-translate-x-full': !@entangle('sidebarOpen')}"
    >
        <div class="flex flex-col h-full">
            <!-- Logo/Brand -->
            <div class="flex items-center h-16 px-6 bg-slate-900 border-b border-slate-700">
                <img class="h-8 w-auto" src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" alt="Bags & Tea" onerror="this.style.display='none'">
                <div class="ml-3">
                    <h1 class="text-white text-xl font-bold">Admin</h1>
                    <p class="text-slate-300 text-xs">Bags & Tea</p>
                </div>
            </div>

            <!-- User Info -->
            @if($currentUser)
            <div class="flex items-center px-6 py-4 bg-slate-700 border-b border-slate-600">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center shadow-lg">
                        <span class="text-sm font-semibold text-white">
                            {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $currentUser->name }}</p>
                    <p class="text-xs text-slate-300">Administrator</p>
                </div>
                <!-- Online indicator -->
                <div class="flex-shrink-0">
                    <span class="inline-block h-2 w-2 rounded-full bg-green-400"></span>
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @foreach($navigationItems as $item)
                <a 
                    href="{{ route($item['route']) }}" 
                    class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ $this->isActiveRoute($item['route']) ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"
                    wire:click="closeSidebar"
                >
                    <svg class="mr-3 h-5 w-5 {{ $this->isActiveRoute($item['route']) ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['name'] }}
                    @if($this->isActiveRoute($item['route']))
                        <span class="ml-auto">
                            <div class="h-2 w-2 rounded-full bg-white"></div>
                        </span>
                    @endif
                </a>
                @endforeach
            </nav>

            <!-- Bottom section -->
            <div class="flex-shrink-0 border-t border-slate-700">
                <!-- Quick actions -->
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <span>Quick Actions</span>
                    </div>
                    <div class="mt-2 flex space-x-2">
                        <button class="flex-1 px-2 py-1 text-xs bg-slate-700 text-slate-300 rounded hover:bg-slate-600 transition-colors">
                            Settings
                        </button>
                        <button class="flex-1 px-2 py-1 text-xs bg-slate-700 text-slate-300 rounded hover:bg-slate-600 transition-colors">
                            Help
                        </button>
                    </div>
                </div>
                
                <!-- Logout -->
                <div class="px-4 py-4">
                    <button 
                        wire:click="logout"
                        onclick="return confirm('Are you sure you want to logout?')"
                        class="group flex items-center w-full px-3 py-2 text-sm font-medium text-slate-300 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-200"
                    >
                        <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </aside>
</div>