<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $isEditing ? 'Edit Product' : 'Create New Product' }}
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    {{ $isEditing ? 'Update your product information' : 'Add a new product to your catalog' }}
                </p>
            </div>
            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.products') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Products
                </a>
                @if($isEditing && $product)
                    <a href="{{ route('product.show.' . app()->getLocale(), ['locale' => app()->getLocale(), 'productSlug' => $product->getTranslation('slug', app()->getLocale()) ?: $product->slug]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Product
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-md p-4 shadow-md" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="show = true; setTimeout(() => show = false, 5000)"
             wire:key="success-{{ session('success') }}-{{ now()->timestamp }}"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform translate-y-0" 
             x-transition:leave-end="opacity-0 transform translate-y-2">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="flex items-center">
                        <p class="text-sm font-medium text-green-800">
                            Success!
                        </p>
                    </div>
                    <div class="mt-1">
                        <p class="text-sm text-green-700">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 transition-colors duration-150">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 rounded-md p-4 shadow-md" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="show = true; setTimeout(() => show = false, 5000)"
             wire:key="error-{{ count($errors) }}-{{ now()->timestamp }}"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform translate-y-2" 
             x-transition:enter-end="opacity-100 transform translate-y-0" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform translate-y-0" 
             x-transition:leave-end="opacity-0 transform translate-y-2">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="flex items-center">
                        <p class="text-sm font-medium text-red-800">
                            Validation Error!
                        </p>
                    </div>
                    <div class="mt-1">
                        <p class="text-sm text-red-700 mb-2">
                            There were {{ $errors->count() }} error(s) with your submission:
                        </p>
                        <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600 transition-colors duration-150">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Module 1: Basic Information -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                <p class="mt-1 text-sm text-gray-600">Enter the basic details of your product</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Product Names -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name (English)</label>
                        <input wire:model.live="name_en" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product name in English">
                        @error('name_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name (Spanish)</label>
                        <input wire:model.live="name_es" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product name in Spanish">
                        @error('name_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Slugs -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug (English)</label>
                        <input wire:model="slug_en" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="product-slug-en">
                        @error('slug_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug (Spanish)</label>
                        <input wire:model="slug_es" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="product-slug-es">
                        @error('slug_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Brand and Product Type -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select wire:model="brand_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Type</label>
                        <select wire:model="product_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select product type</option>
                            <option value="simple">Simple</option>
                            <option value="variable">Variable</option>
                        </select>
                        @error('product_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Vendor and Quality -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                        <select wire:model="vendor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->business_name ?: $vendor->user->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quality</label>
                        <select wire:model="quality_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select quality</option>
                            @foreach ($qualities as $quality)
                                <option value="{{ $quality->id }}">{{ $quality->name }}</option>
                            @endforeach
                        </select>
                        @error('quality_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <!-- Origin country and status -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Origin Country</label>
                        <select wire:model="origin_country" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select country</option>
                            <option value="FR">France</option>
                            <option value="ES">Spain</option>
                            <option value="IT">Italy</option>
                        </select>
                        @error('origin_country') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select status</option>
                            <option value="approved">Approved</option>
                            <option value="pending-review">Pending Review</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- SKU -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit)</label>
                        <input wire:model="sku" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product SKU">
                        @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-sm text-gray-500">Optional unique identifier for inventory management</p>
                    </div>
                    <div>
                        <!-- Empty column for alignment -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Module 2: Media -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-900">Media</h3>
                <p class="mt-1 text-sm text-gray-600">Upload and manage product images</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Media Upload -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Product Images</h4>
                    
                    <!-- All Images (Unified Display) -->
                    @if(!empty($unifiedMedia))
                        <div class="mb-6">
                            <h5 class="text-xs font-medium text-gray-700 mb-3">Product Images ({{ count($unifiedMedia) }}/12)</h5>
                            <p class="text-xs text-gray-500 mb-3">Drag images to reorder. First image will be the primary image.</p>
                            <div id="sortable-images" class="flex flex-wrap gap-4">
                                @foreach($unifiedMedia as $index => $item)
                                    @if($item['type'] === 'existing')
                                        <!-- Existing Media -->
                                        <div class="relative group cursor-move w-40 h-40 flex-shrink-0" wire:key="media-{{ $item['data']['id'] }}" data-id="{{ $item['id'] }}">
                                            <img src="{{ str_starts_with($item['data']['file_path'], 'https://') || str_contains($item['data']['file_path'], 'r2.cloudflarestorage.com') ? $item['data']['file_path'] : asset($item['data']['file_path']) }}" alt="{{ $item['data']['alt_text'] ?? '' }}" class="w-full h-40 object-cover rounded-lg shadow-sm border-2 {{ $item['is_primary'] ? 'border-indigo-500' : 'border-gray-200' }}">
                                            @if($item['is_primary'])
                                                <div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                                    Primary
                                                </div>
                                            @endif
                                            <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                                #{{ $item['position'] }}
                                            </div>
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                <button wire:click="removeMedia('{{ $item['data']['id'] }}')" type="button" class="remove-button text-white text-xs px-2 py-1 bg-red-600 rounded hover:bg-red-700">
                                                    <span wire:loading.remove wire:target="removeMedia">Remove</span>
                                                    <span wire:loading wire:target="removeMedia">Removing...</span>
                                                </button>
                                            </div>
                                            <!-- Drag handle -->
                                            <div class="absolute bottom-2 right-2 text-white opacity-75">
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M7 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @else
                                        <!-- New Media -->
                                        @if(is_object($item['data']) && method_exists($item['data'], 'getClientOriginalName'))
                                            <div class="relative group cursor-move w-40 h-40 flex-shrink-0 border-2 border-green-300 rounded-lg bg-white overflow-hidden" data-id="{{ $item['id'] }}">
                                                <img src="{{ $item['data']->temporaryUrl() }}" alt="{{ $item['data']->getClientOriginalName() }}" class="w-full h-40 object-cover">
                                                @if($item['is_primary'])
                                                    <div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                                        Primary
                                                    </div>
                                                @else
                                                    <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                                                        New
                                                    </div>
                                                @endif
                                                <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                                    #{{ $item['position'] }}
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                                    <div class="text-center">
                                                        <p class="text-white text-xs font-medium">{{ $item['data']->getClientOriginalName() }}</p>
                                                        <p class="text-green-300 text-xs mt-1">Ready to upload</p>
                                                        <button wire:click="removeNewMedia({{ str_replace('new_', '', $item['id']) }})" type="button" class="remove-button text-white text-xs px-2 py-1 bg-red-600 rounded hover:bg-red-700 mt-2">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Drag handle -->
                                                <div class="absolute bottom-2 right-2 text-white opacity-75">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M7 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 2a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM17 14a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Media -->
                    @php
                        $totalImages = count($unifiedMedia ?? []);
                        $maxImages = 12;
                        $canUpload = $totalImages < $maxImages;
                    @endphp
                    
                    @if($canUpload)
                        <label class="block border-2 border-dashed border-gray-300 hover:border-indigo-400 rounded-lg p-6 text-center cursor-pointer transition-colors duration-200">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <span class="mt-2 block text-sm font-medium text-gray-900">Upload new images</span>
                                <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $maxImages - $totalImages }} more images can be added</p>
                            </div>
                            <input wire:model="media" type="file" class="sr-only" multiple accept="image/*">
                        </label>
                    @else
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center opacity-50">
                            <svg class="mx-auto h-12 w-12 text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <span class="mt-2 block text-sm font-medium text-gray-500">Maximum images reached ({{ $maxImages }})</span>
                                <p class="mt-1 text-sm text-gray-400">Remove some images to upload new ones</p>
                            </div>
                        </div>
                    @endif
                    
                    @error('media') 
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>
            </div>
        </div>

        <!-- Module 3: Categories & Attributes -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                <h3 class="text-lg font-semibold text-gray-900">Categories & Attributes</h3>
                <p class="mt-1 text-sm text-gray-600">Organize your product with categories and attributes</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                    <div class="space-y-4">
                        @foreach($categoriesByParent as $parentGroup)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <h5 class="font-medium text-gray-800 mb-3">{{ $parentGroup['parent_name'] }}</h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($parentGroup['children'] as $category)
                                        <div class="flex items-center">
                                            <input wire:model="selectedCategories" type="checkbox" value="{{ $category->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-700">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedCategories') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Attributes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attributes</label>
                    <div class="space-y-4">
                        @foreach($attributesByParent as $parentGroup)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <h5 class="font-medium text-gray-800 mb-3">{{ $parentGroup['parent_name'] }}</h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($parentGroup['children'] as $attribute)
                                        <div class="flex items-center">
                                            <input wire:model="selectedAttributes" type="checkbox" value="{{ $attribute->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm text-gray-700">{{ $attribute->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedAttributes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Module 4: Descriptions & Details -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50">
                <h3 class="text-lg font-semibold text-gray-900">Descriptions & Details</h3>
                <p class="mt-1 text-sm text-gray-600">Provide detailed information about your product</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Description 1 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description 1 (English)</label>
                        <textarea wire:model="description_1_en" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product description in English"></textarea>
                        @error('description_1_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description 1 (Spanish)</label>
                        <textarea wire:model="description_1_es" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter product description in Spanish"></textarea>
                        @error('description_1_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description 2 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description 2 (English)</label>
                        <textarea wire:model="description_2_en" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Additional details in English"></textarea>
                        @error('description_2_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description 2 (Spanish)</label>
                        <textarea wire:model="description_2_es" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Additional details in Spanish"></textarea>
                        @error('description_2_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <!-- SEO Meta -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">SEO Meta Information</h4>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title (English)</label>
                            <input wire:model="meta_title_en" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="SEO title for English">
                            @error('meta_title_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title (Spanish)</label>
                            <input wire:model="meta_title_es" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="SEO title for Spanish">
                            @error('meta_title_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description (English)</label>
                            <textarea wire:model="meta_description_en" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="SEO description for English"></textarea>
                            @error('meta_description_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description (Spanish)</label>
                            <textarea wire:model="meta_description_es" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="SEO description for Spanish"></textarea>
                            @error('meta_description_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module 5: Pricing & Inventory -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h3>
                <p class="mt-1 text-sm text-gray-600">Set pricing and manage inventory</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Dimensions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Width (cm)</label>
                        <input wire:model="width" type="number" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.0">
                        @error('width') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                        <input wire:model="height" type="number" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.0">
                        @error('height') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Depth (cm)</label>
                        <input wire:model="depth" type="number" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.0">
                        @error('depth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (€)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">€</span>
                            <input wire:model="price" type="number" step="0.01" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discounted Price (€)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">€</span>
                            <input wire:model="discounted_price" type="number" step="0.01" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                        @error('discounted_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Final Deal Price (€)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">€</span>
                            <input wire:model="deal_price" type="number" step="0.01" class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                        @error('deal_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Inventory -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input wire:model="stock" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 100, unlimited">
                        @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Flags -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="flex items-center">
                        <input wire:model="out_of_stock" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Out of Stock</label>
                    </div>
                    <div class="flex items-center">
                        <input wire:model="is_sold_out" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Sold Out</label>
                    </div>
                    <div class="flex items-center">
                        <input wire:model="is_hidden" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Hidden From Shop</label>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input wire:model="featured" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Featured Product</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Position</label>
                        <input wire:model="featured_position" type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                        @error('featured_position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center pt-6">
            <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                <svg class="-ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $isEditing ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let sortableInstance = null;
    
    function initializeSortable() {
        const sortableElement = document.getElementById('sortable-images');
        
        // Destroy existing instance if it exists
        if (sortableInstance) {
            sortableInstance.destroy();
            sortableInstance = null;
        }
        
        if (sortableElement) {
            sortableInstance = Sortable.create(sortableElement, {
                animation: 150,
                forceFallback: true,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                filter: '.remove-button',
                preventOnFilter: false,
                onStart: function(evt) {
                    evt.item.style.zIndex = '9999';
                    evt.item.style.opacity = '0.7';
                    evt.item.style.transform = 'rotate(2deg) scale(1.05)';
                    evt.item.classList.add('shadow-lg');
                },
                onEnd: function(evt) {
                    evt.item.style.zIndex = '';
                    evt.item.style.opacity = '';
                    evt.item.style.transform = '';
                    evt.item.classList.remove('shadow-lg');
                    
                    // Get all item IDs in new order
                    const orderedIds = Array.from(sortableElement.children).map(child => {
                        return child.getAttribute('data-id');
                    }).filter(id => id !== null);
                    
                    console.log('New order:', orderedIds);
                    
                    // Call Livewire method to update order
                    if (window.Livewire) {
                        @this.call('reorderImages', orderedIds);
                    }
                }
            });
        }
    }
    
    // Initialize on page load
    initializeSortable();
    
    // Re-initialize after Livewire updates
    document.addEventListener('livewire:morph.updated', function() {
        setTimeout(initializeSortable, 200);
    });
    
    // Also listen for Livewire navigated event
    document.addEventListener('livewire:navigated', function() {
        setTimeout(initializeSortable, 200);
    });
    
    // Ensure remove buttons work with SortableJS
    document.addEventListener('mousedown', function(e) {
        if (e.target.matches('.remove-button') || e.target.closest('.remove-button')) {
            e.stopPropagation();
        }
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.matches('.remove-button') || e.target.closest('.remove-button')) {
            e.stopPropagation();
        }
    });
    
    // Listen for scroll-to-top events from Livewire
    window.addEventListener('scroll-to-top', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>