<div x-data="{ open: @entangle('sidebarOpen') }" @toggle-sidebar.window="open = true">
    <!-- Mobile overlay -->
    <div
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="lg:hidden fixed inset-0 bg-black/50 z-30"
        @click="open = false"
    ></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-800 transform transition-transform duration-200 ease-in-out lg:translate-x-0 h-screen sidebar-scroll"
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <div class="flex flex-col h-full">
            <!-- Logo/Brand with close button on mobile -->
            <div class="flex items-center justify-between h-16 bg-slate-900 border-b border-slate-700 px-4">
                <div>
                    <h1 class="text-white text-xl font-bold">Admin</h1>
                    <p class="text-slate-300 text-xs">Bags & Tea</p>
                </div>
                <!-- Close button for mobile -->
                <button
                    @click="open = false"
                    class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
                    <p class="text-xs text-slate-300">{{ $userRoleDisplay }}</p>
                </div>
                <!-- Online indicator -->
                <div class="flex-shrink-0">
                    <span class="inline-block h-2 w-2 rounded-full bg-green-400"></span>
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @foreach($navigationItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-150 {{ $this->isActiveRoute($item['route']) ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}"
                    @click="open = false"
                >
                    <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ $this->isActiveRoute($item['route']) ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    <span class="truncate">{{ $item['name'] }}</span>
                    @if($this->isActiveRoute($item['route']))
                        <span class="ml-auto flex-shrink-0">
                            <div class="h-2 w-2 rounded-full bg-white"></div>
                        </span>
                    @endif
                </a>
                @endforeach
            </nav>

            <!-- Bottom section - Logout -->
            <div class="flex-shrink-0 border-t border-slate-700 p-4">
                <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                    @csrf
                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to logout?')"
                        class="group flex items-center w-full px-3 py-2.5 text-sm font-medium text-slate-300 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-150"
                    >
                        <svg class="mr-3 h-5 w-5 text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>