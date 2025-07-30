@section('page-title', 'Form Submissions')

<div>
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Form Submissions</h1>
            <p class="mt-2 text-sm text-gray-700">View and manage form submissions from your website</p>
        </div>
    </div>

    <!-- Form Selector -->
    @if(!empty($forms))
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4">
                <label for="form-selector" class="block text-sm font-medium text-gray-700 mb-2">
                    Select Form to View Submissions
                </label>
                <select wire:model.live="selectedFormId" id="form-selector" 
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Choose a form...</option>
                    @foreach($forms as $form)
                        <option value="{{ $form['id'] }}">
                            {{ $form['form_name'] }} ({{ $form['submissions_count'] }} submissions)
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    @if($isLoading)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Loading submissions...</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Submissions Table -->
    @if(!$isLoading && !empty($selectedFormId))
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $this->getSelectedFormName() }} Submissions
                </h3>
                @if(!empty($paginationData))
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $paginationData['total'] }} total submissions
                    </p>
                @endif
            </div>

            @if(!empty($submissions))
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted At
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Preview
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($submissions as $submission)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $submission['submitted_at'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div class="max-w-md truncate">
                                            {{ $submission['preview'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.form-submissions.detail', $submission['id']) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(!empty($paginationData) && $paginationData['last_page'] > 1)
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                @if($paginationData['current_page'] > 1)
                                    <button wire:click="previousPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Previous
                                    </button>
                                @endif
                                @if($paginationData['has_more_pages'])
                                    <button wire:click="nextPage" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Next
                                    </button>
                                @endif
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing page {{ $paginationData['current_page'] }} of {{ $paginationData['last_page'] }}
                                        ({{ $paginationData['total'] }} total results)
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        @if($paginationData['current_page'] > 1)
                                            <button wire:click="previousPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                Previous
                                            </button>
                                        @endif
                                        @if($paginationData['has_more_pages'])
                                            <button wire:click="nextPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                Next
                                            </button>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions found</h3>
                    <p class="mt-1 text-sm text-gray-500">This form hasn't received any submissions yet.</p>
                </div>
            @endif
        </div>
    @endif

    <!-- No Forms State -->
    @if(empty($forms))
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No forms available</h3>
                <p class="mt-1 text-sm text-gray-500">There are no active forms in the system to view submissions for.</p>
            </div>
        </div>
    @endif

    <!-- No Form Selected State -->
    @if(!$isLoading && !empty($forms) && empty($selectedFormId))
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Select a form</h3>
                <p class="mt-1 text-sm text-gray-500">Choose a form from the dropdown above to view its submissions.</p>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('error'))
        <div class="fixed top-4 right-4 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg z-50" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Error</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>