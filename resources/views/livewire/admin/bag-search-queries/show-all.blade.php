<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Bag Search Queries</h1>
                <a href="{{ route('admin.bag-search-queries.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Query
                </a>
            </div>

            @if (session()->has('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-md p-4 shadow-md"
                     x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 5000)"
                     x-transition>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
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
                                placeholder="Search by name or brand..."
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
                            <th wire:click="sortByColumn('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Name
                                @if($sortBy === 'name')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('brand')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Brand
                                @if($sortBy === 'brand')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('ideal_price')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Ideal Price
                                @if($sortBy === 'ideal_price')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price Range
                            </th>
                            <th wire:click="sortByColumn('max_pages')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Max Pages
                                @if($sortBy === 'max_pages')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('is_active')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Status
                                @if($sortBy === 'is_active')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('last_scanned_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Last Scanned
                                @if($sortBy === 'last_scanned_at')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($queries as $query)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $query->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $query->brand ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($query->ideal_price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $query->min_price ? number_format($query->min_price, 2) : '-' }}
                                    -
                                    {{ $query->max_price ? number_format($query->max_price, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $query->max_pages }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        wire:click="toggleActive('{{ $query->id }}')"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $query->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                    >
                                        {{ $query->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $query->last_scanned_at ? $query->last_scanned_at->format('M d, Y H:i') : 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.bag-search-queries.edit', $query->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <button
                                            wire:click="delete('{{ $query->id }}')"
                                            wire:confirm="Are you sure you want to delete this search query?"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No bag search queries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($queries->hasPages())
                <div class="mt-6">
                    @livewire('shared/pagination', [
                        'currentPage' => $queries->currentPage(),
                        'totalItems' => $queries->total(),
                        'perPage' => $queries->perPage(),
                        'paginationClass' => 'justify-center'
                    ], key('bag-search-queries-pagination-' . $queries->currentPage() . '-' . $queries->total()))
                </div>
            @endif
        </div>
    </div>
</div>
