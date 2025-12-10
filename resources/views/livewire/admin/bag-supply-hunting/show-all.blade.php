<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Bag Supply Hunting</h1>
                <span class="text-sm text-gray-500">Vinted Listings</span>
            </div>

            <!-- Filters -->
            <div class="mb-6 space-y-4">
                <!-- Search -->
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search by title..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filter dropdowns -->
                <div class="flex flex-wrap gap-2">
                    <select wire:model.live="filterInteresting" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All (Interesting)</option>
                        <option value="1">Interesting</option>
                        <option value="0">Not Interesting</option>
                    </select>
                    <select wire:model.live="filterVerified" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All (Real Product)</option>
                        <option value="1">Real Product</option>
                        <option value="0">Not Real Product</option>
                    </select>
                    <select wire:model.live="filterNotificationSent" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All (Notification)</option>
                        <option value="1">Sent</option>
                        <option value="0">Not Sent</option>
                    </select>
                    <select wire:model.live="perPage" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="block lg:hidden space-y-4">
                @forelse($listings as $listing)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex gap-3">
                            <!-- Image -->
                            <div class="flex-shrink-0">
                                @php
                                    $imageUrl = ($listing->images && count($listing->images) > 0) ? $listing->images[0] : $listing->main_image_url;
                                @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ $listing->url }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 line-clamp-2">
                                    {{ $listing->title }}
                                </a>
                                <p class="text-lg font-bold text-gray-900 mt-1">{{ number_format($listing->price, 2) }} {{ $listing->currency }}</p>
                            </div>
                        </div>
                        <!-- Status badges -->
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $listing->is_interesting ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $listing->is_interesting ? 'Interesting' : 'Not Interesting' }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($listing->is_verified_product === true) bg-blue-100 text-blue-800
                                @elseif($listing->is_verified_product === false) bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-600 @endif">
                                @if($listing->is_verified_product === true) Real Product
                                @elseif($listing->is_verified_product === false) Not Real
                                @else Pending @endif
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $listing->notification_sent ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $listing->notification_sent ? 'Sent' : 'Pending' }}
                            </span>
                        </div>
                        <!-- Dates -->
                        <div class="mt-3 text-xs text-gray-500 space-y-1">
                            <p><span class="font-medium">Published in Marketplace:</span> {{ $listing->uploaded_text ?? ($listing->published_at ? $listing->published_at->format('M d, Y H:i') : '-') }}</p>
                            <p><span class="font-medium">Found:</span> {{ $listing->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        No listings found.
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Image
                            </th>
                            <th wire:click="sortByColumn('title')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Title
                                @if($sortBy === 'title')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('price')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Price
                                @if($sortBy === 'price')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('is_interesting')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Interesting
                                @if($sortBy === 'is_interesting')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('is_verified_product')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Real Product
                                @if($sortBy === 'is_verified_product')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('published_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Published in Marketplace
                                @if($sortBy === 'published_at')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('notification_sent')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Email Notification
                                @if($sortBy === 'notification_sent')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                            <th wire:click="sortByColumn('created_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                Found
                                @if($sortBy === 'created_at')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($listings as $listing)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @php
                                        $imageUrl = ($listing->images && count($listing->images) > 0) ? $listing->images[0] : $listing->main_image_url;
                                    @endphp
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ $listing->url }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline" title="{{ $listing->title }}">
                                        {{ Str::limit($listing->title, 40) }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ number_format($listing->price, 2) }} {{ $listing->currency }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $listing->is_interesting ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $listing->is_interesting ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($listing->is_verified_product === true) bg-blue-100 text-blue-800
                                        @elseif($listing->is_verified_product === false) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-600 @endif">
                                        @if($listing->is_verified_product === true) Real
                                        @elseif($listing->is_verified_product === false) No
                                        @else Pending @endif
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $listing->uploaded_text ?? ($listing->published_at ? $listing->published_at->format('M d, Y H:i') : '-') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $listing->notification_sent ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $listing->notification_sent ? 'Sent' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $listing->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No listings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($totalItems > $perPage)
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    @livewire('shared/pagination', [
                        'currentPage' => $currentPage,
                        'totalItems' => $totalItems,
                        'perPage' => $perPage,
                    ], key('bag-supply-hunting-pagination-' . $currentPage . '-' . $totalItems))
                </div>
            @endif
        </div>
    </div>
</div>
