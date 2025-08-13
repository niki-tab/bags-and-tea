<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Blog Categories Management</h1>
                <a href="{{ route('admin.blog.categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Blog Category
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input 
                                wire:model.live="search"
                                type="text" 
                                placeholder="Search blog categories..."
                                class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <select wire:model.live="perPage" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortByColumn('display_order')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Order
                                    @if($sortBy === 'display_order')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortByColumn('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Name
                                    @if($sortBy === 'name')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortByColumn('slug')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Slug
                                    @if($sortBy === 'slug')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortByColumn('parent_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Parent
                                    @if($sortBy === 'parent_id')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Color
                            </th>
                            <th wire:click="sortByColumn('is_active')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Status
                                    @if($sortBy === 'is_active')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortByColumn('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center">
                                    Created
                                    @if($sortBy === 'created_at')
                                        @if($sortDirection === 'asc')
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $category->display_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $category->getTranslation('name', 'en') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $category->getTranslation('name', 'es') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $category->getTranslation('slug', 'en') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $category->getTranslation('slug', 'es') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->parent ? $category->parent->getTranslation('name', 'en') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($category->color)
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded-full border border-gray-300 mr-2" style="background-color: {{ $category->color }}"></div>
                                            <span class="text-sm text-gray-500">{{ $category->color }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        wire:click="toggleActive('{{ $category->id }}')"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    >
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.blog.categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Edit
                                    </a>
                                    <button 
                                        wire:click="deleteCategory('{{ $category->id }}')" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this blog category?')"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No blog categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>