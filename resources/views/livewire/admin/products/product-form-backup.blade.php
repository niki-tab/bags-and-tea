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
            <a href="{{ route('admin.products') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-md p-4 shadow-md" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000); window.scrollTo({ top: 0, behavior: 'smooth' })"
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
             x-init="window.scrollTo({ top: 0, behavior: 'smooth' })"
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
                            <input wire:model="product_type" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Handbag, Purse, Backpack">
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
            </div>
        </div>

        <!-- Module 2: Media & Categories -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="text-lg font-semibold text-gray-900">Media & Categories</h3>
                <p class="mt-1 text-sm text-gray-600">Upload product images and assign categories</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Media Upload -->
                <div class="bg-gray-50 rounded-lg p-6" x-data="{ 
                    previews: [],
                    fileCount: 0,
                    generatePreviews(files) {
                        this.previews = [];
                        this.fileCount = files.length;
                        Array.from(files).forEach((file, index) => {
                            let reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews[index] = {
                                    src: e.target.result,
                                    name: file.name,
                                    size: file.size
                                };
                            };
                            reader.readAsDataURL(file);
                        });
                    },
                    removeFile(index) {
                        let input = document.querySelector('input[type=file][multiple]');
                        if (input && input.files) {
                            let dt = new DataTransfer();
                            let files = Array.from(input.files);
                            files.splice(index, 1);
                            files.forEach(file => dt.items.add(file));
                            input.files = dt.files;
                            this.generatePreviews(input.files);
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Product Images</h4>
                    
                    <!-- Existing Media -->
                    @if(!empty($existingMedia))
                        <div class="mb-6">
                            <h5 class="text-xs font-medium text-gray-700 mb-3">Current Images</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($existingMedia as $media)
                                    <div class="relative group">
                                        <img src="{{ asset($media['file_path']) }}" alt="{{ $media['alt_text'] }}" class="w-full h-32 object-cover rounded-lg shadow-sm border-2 {{ $media['is_primary'] ? 'border-indigo-500' : 'border-gray-200' }}">
                                        @if($media['is_primary'])
                                            <div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                                                Primary
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                            <button wire:click="setPrimaryMedia('{{ $media['id'] }}')" type="button" class="text-white text-xs px-2 py-1 bg-indigo-600 rounded mr-2 hover:bg-indigo-700">
                                                {{ $media['is_primary'] ? 'Primary' : 'Set Primary' }}
                                            </button>
                                            <button wire:click="removeMedia('{{ $media['id'] }}')" type="button" class="text-white text-xs px-2 py-1 bg-red-600 rounded hover:bg-red-700">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images Preview -->
                    <div x-show="fileCount > 0" style="display: none;">
                        <div class="mb-6">
                            <h5 class="text-xs font-medium text-gray-700 mb-3">New Images (<span x-text="fileCount"></span> to upload)</h5>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ previews: {} }" x-init="
                                $wire.on('mediaUpdated', () => {
                                    // Re-generate previews when media updates
                                    setTimeout(() => {
                                        let input = document.querySelector('input[type=file][multiple]');
                                        if (input && input.files) {
                                            previews = {};
                                            Array.from(input.files).forEach((file, index) => {
                                                let reader = new FileReader();
                                                reader.onload = function(e) {
                                                    previews[index] = e.target.result;
                                                    $nextTick(() => { /* Force Alpine to re-render */ });
                                                };
                                                reader.readAsDataURL(file);
                                            });
                                        }
                                    }, 100);
                                });
                            ">
                                @foreach($media as $index => $file)
                                    <div class="relative group border-2 border-dashed border-green-300 rounded-lg bg-green-50">
                                        <div class="w-full h-32 flex items-center justify-center rounded-lg" x-show="!previews[{{ $index }}]">
                                            <div class="text-center">
                                                <svg class="mx-auto h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-xs text-green-600 mt-1">Loading...</p>
                                            </div>
                                        </div>
                                        <img x-show="previews[{{ $index }}]" x-bind:src="previews[{{ $index }}]" class="w-full h-32 object-cover rounded-lg">
                                        <div class="absolute top-2 right-2">
                                            <button wire:click="removeNewMedia({{ $index }})" type="button" class="bg-red-600 text-white rounded-full p-1 text-xs hover:bg-red-700 shadow-lg">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="absolute bottom-2 left-2 right-2">
                                            <div class="bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded text-center">
                                                Ready to upload
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Media -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition-colors duration-200" 
                         x-data="{ 
                            isDragging: false,
                            handleFiles(files) {
                                let input = document.querySelector('input[type=file][multiple]');
                                input.files = files;
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                         }"
                         @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
                         @dragover.prevent="isDragging = true"
                         @dragenter.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         :class="{ 'border-indigo-400 bg-indigo-50': isDragging }">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="mt-4">
                            <label class="cursor-pointer">
                                <span class="mt-2 block text-sm font-medium text-gray-900">Upload new images</span>
                                <input wire:model="media" type="file" class="sr-only" multiple accept="image/*" 
                                       x-ref="fileInput"
                                       @change="
                                           let fileArray = Array.from($event.target.files);
                                           if (fileArray.length > 0) {
                                               // Show the preview section
                                               $el.closest('[x-data]').querySelector('[x-show]').style.display = 'block';
                                               // Generate previews
                                               fileArray.forEach((file, index) => {
                                                   let reader = new FileReader();
                                                   reader.onload = (e) => {
                                                       // Update Alpine data
                                                       let container = $el.closest('[x-data]');
                                                       if (container._x_dataStack && container._x_dataStack[0]) {
                                                           container._x_dataStack[0].previews[index] = {
                                                               src: e.target.result,
                                                               name: file.name
                                                           };
                                                           container._x_dataStack[0].fileCount = fileArray.length;
                                                       }
                                                   };
                                                   reader.readAsDataURL(file);
                                               });
                                           }
                                       ">
                            </label>
                            <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                            <p class="mt-1 text-xs text-gray-400">Drag and drop files here or click to browse</p>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                            <div class="flex items-center">
                                <input wire:model="selectedCategories" type="checkbox" value="{{ $category->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedCategories') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Attributes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attributes</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($availableAttributes as $attribute)
                            <div class="flex items-center">
                                <input wire:model="selectedAttributes" type="checkbox" value="{{ $attribute->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">{{ $attribute->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedAttributes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Module 3: Descriptions & Details -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
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

                    <!-- Origin -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Origin Country(English)</label>
                            <input wire:model="origin_en" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Made in Italy">
                            @error('origin_en') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Origin Country (Spanish)</label>
                            <input wire:model="origin_es" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Hecho en Italia">
                            @error('origin_es') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

        <!-- Module 4: Pricing & Inventory -->
        <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <h3 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h3>
                <p class="mt-1 text-sm text-gray-600">Set pricing and manage inventory</p>
            </div>
                <div class="p-6 space-y-6">
                    <!-- Pricing -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                    </div>

                    <!-- Selling Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sell Unit</label>
                            <input wire:model="sell_unit" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., piece, set, pair">
                            @error('sell_unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sell Mode</label>
                            <select wire:model="sell_mode" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select sell mode</option>
                                <option value="online">Online</option>
                                <option value="in-store">In Store</option>
                                <option value="both">Both</option>
                            </select>
                            @error('sell_mode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Inventory -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                            <input wire:model="stock" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 100, unlimited">
                            @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock Unit</label>
                            <input wire:model="stock_unit" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., pieces, units">
                            @error('stock_unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Flags -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="flex items-center">
                            <input wire:model="out_of_stock" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900">Out of Stock</label>
                        </div>
                        <div class="flex items-center">
                            <input wire:model="is_sold_out" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900"> Sold Out</label>
                        </div>
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

@push('scripts')
<script>
    // Auto-save draft every 30 seconds
    setInterval(function() {
        // You can implement auto-save functionality here
        console.log('Auto-save triggered');
    }, 30000);
</script>
@endpush