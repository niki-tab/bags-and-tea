@section('page-title', $isEditing ? 'Edit Article' : 'Add Article')

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $isEditing ? 'Edit Article' : 'Add New Article' }}
            </h1>
            <p class="mt-2 text-sm text-gray-700">
                {{ $isEditing ? 'Update article content and settings' : 'Create a new blog article with multi-language support' }}
            </p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if($showSuccessMessage)
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ $successMessage }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($showErrorMessage)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Language Tabs -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            @foreach($availableLocales as $locale)
                                <button 
                                    type="button"
                                    wire:click="switchLocale('{{ $locale }}')"
                                    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200 {{ $currentLocale === $locale ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                >
                                    {{ strtoupper($locale) }}
                                    @if($currentLocale === $locale)
                                        <span class="ml-2 inline-block w-2 h-2 bg-indigo-500 rounded-full"></span>
                                    @endif
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Title Field -->
                        <div>
                            <label for="title_{{ $currentLocale }}" class="block text-sm font-medium text-gray-700 mb-2">
                                Title ({{ strtoupper($currentLocale) }}) *
                            </label>
                            <input 
                                type="text" 
                                id="title_{{ $currentLocale }}"
                                wire:model.debounce.500ms="title.{{ $currentLocale }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title.' . $currentLocale) border-red-300 @enderror"
                                placeholder="Enter article title in {{ strtoupper($currentLocale) }}"
                            >
                            @error('title.' . $currentLocale)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug Field -->
                        <div>
                            <label for="slug_{{ $currentLocale }}" class="block text-sm font-medium text-gray-700 mb-2">
                                Slug ({{ strtoupper($currentLocale) }}) *
                                <button 
                                    type="button" 
                                    wire:click="generateSlug('{{ $currentLocale }}')"
                                    class="ml-2 text-xs text-indigo-600 hover:text-indigo-500"
                                >
                                    Generate from title
                                </button>
                            </label>
                            <input 
                                type="text" 
                                id="slug_{{ $currentLocale }}"
                                wire:model="slug.{{ $currentLocale }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('slug.' . $currentLocale) border-red-300 @enderror"
                                placeholder="article-slug-{{ strtolower($currentLocale) }}"
                            >
                            @error('slug.' . $currentLocale)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body Field with WYSIWYG -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Content ({{ strtoupper($currentLocale) }}) *
                            </label>
                            <div wire:ignore>
                                @foreach($availableLocales as $locale)
                                    <!-- Hidden input for Livewire binding -->
                                    <input 
                                        type="hidden" 
                                        id="body_{{ $locale }}"
                                        wire:model="body.{{ $locale }}"
                                    >
                                @endforeach
                                <!-- Single Quill editor container -->
                                <div 
                                    id="quill-editor-container" 
                                    class="quill-editor @error('body.' . $currentLocale) border-red-300 @enderror"
                                ></div>
                            </div>
                            @error('body.' . $currentLocale)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Fields -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Meta Data ({{ strtoupper($currentLocale) }})</h3>
                            
                            <div class="space-y-4">
                                <!-- Meta Title -->
                                <div>
                                    <label for="meta_title_{{ $currentLocale }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="meta_title_{{ $currentLocale }}"
                                        wire:model="meta_title.{{ $currentLocale }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        maxlength="60"
                                        placeholder="SEO title for search engines"
                                    >
                                    <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <label for="meta_description_{{ $currentLocale }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Description
                                    </label>
                                    <textarea 
                                        id="meta_description_{{ $currentLocale }}"
                                        wire:model="meta_description.{{ $currentLocale }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        rows="3"
                                        maxlength="160"
                                        placeholder="Brief description for search engine results"
                                    ></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                                </div>

                                <!-- Meta Keywords -->
                                <div>
                                    <label for="meta_keywords_{{ $currentLocale }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Keywords
                                    </label>
                                    <input 
                                        type="text" 
                                        id="meta_keywords_{{ $currentLocale }}"
                                        wire:model="meta_keywords.{{ $currentLocale }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="keyword1, keyword2, keyword3"
                                    >
                                    <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Publish Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                Status *
                            </label>
                            <select 
                                id="state"
                                wire:model="state"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('state') border-red-300 @enderror"
                            >
                                @foreach($this->getStateOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Main Image -->
                        <div>
                            <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Featured Image
                            </label>
                            <input 
                                type="text" 
                                id="main_image"
                                wire:model="main_image"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="/images/articles/image.jpg"
                            >
                            <p class="mt-1 text-xs text-gray-500">Enter image path or URL</p>
                            
                            @if($main_image)
                                <div class="mt-3">
                                    <img src="{{ asset($main_image) }}" alt="Featured image preview" class="w-full h-32 object-cover rounded-md border border-gray-200" onerror="this.style.display='none'">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Categories</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($this->getAvailableCategories() as $category)
                                <label class="flex items-start space-x-3">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $category->id }}"
                                        wire:model="selectedCategories"
                                        class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $category->getTranslation('name', $currentLocale) ?: $category->getTranslation('name', 'en') }}
                                        </span>
                                        @if($category->getTranslation('description_1', $currentLocale) ?: $category->getTranslation('description_1', 'en'))
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($category->getTranslation('description_1', $currentLocale) ?: $category->getTranslation('description_1', 'en'), 60) }}
                                            </p>
                                        @endif
                                        @if($category->color)
                                            <div class="flex items-center mt-1">
                                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></div>
                                                <span class="text-xs text-gray-400">{{ $category->color }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500">No categories available. <a href="/admin-panel/blog/categories" class="text-indigo-600 hover:text-indigo-500">Create categories first</a>.</p>
                            @endforelse
                        </div>
                        
                        @if(count($selectedCategories) > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600">
                                    {{ count($selectedCategories) }} {{ count($selectedCategories) === 1 ? 'category' : 'categories' }} selected
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 space-y-4">
                        <button 
                            type="submit" 
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>
                                @if($isEditing)
                                    Update Article
                                @else
                                    Create Article
                                @endif
                            </span>
                            <span wire:loading>
                                Saving...
                            </span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="cancel"
                            class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Cancel
                        </button>
                    </div>
                </div>

                @if($isEditing && $article)
                    <!-- Article Info -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Article Information</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID</dt>
                                <dd class="text-sm text-gray-900 font-mono break-all">{{ $article->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $article->created_at ? \Carbon\Carbon::parse($article->created_at)->format('M j, Y g:i A') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $article->updated_at ? \Carbon\Carbon::parse($article->updated_at)->format('M j, Y g:i A') : 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor {
        background: white;
    }
    .ql-editor {
        min-height: 200px;
        font-size: 14px;
        line-height: 1.6;
    }
    .ql-toolbar.ql-snow {
        border-top: 1px solid #d1d5db;
        border-left: 1px solid #d1d5db;
        border-right: 1px solid #d1d5db;
        border-bottom: none;
    }
    .ql-container.ql-snow {
        border-bottom: 1px solid #d1d5db;
        border-left: 1px solid #d1d5db;
        border-right: 1px solid #d1d5db;
        border-top: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let quill;
    let currentLocale = '{{ $currentLocale }}';
    let contentCache = {
        'en': '',
        'es': ''
    };

    // Initialize single Quill editor
    function initQuill() {
        quill = new Quill('#quill-editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Enter article content in ' + currentLocale.toUpperCase() + '...'
        });

        // Load initial content from all hidden inputs
        ['en', 'es'].forEach(function(locale) {
            const hiddenInput = document.getElementById('body_' + locale);
            if (hiddenInput && hiddenInput.value) {
                contentCache[locale] = hiddenInput.value;
            }
        });

        // Set initial content for current locale
        if (contentCache[currentLocale]) {
            quill.root.innerHTML = contentCache[currentLocale];
        }

        // Update content when editor changes
        quill.on('text-change', function() {
            const content = quill.root.innerHTML;
            contentCache[currentLocale] = content;
            
            // Update hidden input
            const hiddenInput = document.getElementById('body_' + currentLocale);
            if (hiddenInput) {
                hiddenInput.value = content;
            }
            
            // Update Livewire property
            @this.set('body.' + currentLocale, content);
        });
    }

    // Switch content when locale changes
    function switchLocaleContent(newLocale) {
        if (!quill) return;

        // Save current content
        if (currentLocale) {
            contentCache[currentLocale] = quill.root.innerHTML;
            const currentHiddenInput = document.getElementById('body_' + currentLocale);
            if (currentHiddenInput) {
                currentHiddenInput.value = contentCache[currentLocale];
            }
        }

        // Load new locale content
        currentLocale = newLocale;
        const newContent = contentCache[newLocale] || '';
        quill.root.innerHTML = newContent;
        
        // Update placeholder
        quill.root.dataset.placeholder = 'Enter article content in ' + newLocale.toUpperCase() + '...';
        
        // Focus editor
        quill.focus();
    }

    // Initialize editor
    initQuill();

    // Listen for locale changes
    window.addEventListener('locale-changed', function(event) {
        setTimeout(function() {
            switchLocaleContent(event.detail.locale);
        }, 50);
    });
});
</script>
@endpush