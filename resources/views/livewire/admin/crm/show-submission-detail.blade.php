@section('page-title', 'Form Submission Detail')

<div>
    <!-- Loading State -->
    @if($isLoading)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Loading submission details...</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Not Found State -->
    @if(!$isLoading && $notFound)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Submission not found</h3>
                <p class="mt-1 text-sm text-gray-500">The requested submission could not be found or may have been deleted.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.form-submissions') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Back to Form Submissions
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Submission Detail -->
    @if(!$isLoading && !$notFound && !empty($submission))
        <!-- Breadcrumb & Actions -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.form-submissions') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                                Form Submissions
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Submission Detail</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $submission['form_name'] }} Submission</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Submitted on {{ \Carbon\Carbon::parse($submission['submitted_at'])->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.form-submissions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Submission Answers -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Form Responses</h3>
            </div>
            <div class="px-6 py-6">
                @php
                    $formattedAnswers = $this->getFormattedAnswers();
                @endphp
                
                @if(!empty($formattedAnswers))
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        @foreach($formattedAnswers as $answer)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">{{ $answer['label'] }}</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($answer['type'] === 'email')
                                        <a href="mailto:{{ $answer['value'] }}" class="text-indigo-600 hover:text-indigo-500">
                                            {{ $answer['value'] }}
                                        </a>
                                    @elseif($answer['type'] === 'phone')
                                        <a href="tel:{{ $answer['value'] }}" class="text-indigo-600 hover:text-indigo-500">
                                            {{ $answer['value'] }}
                                        </a>
                                    @elseif($answer['type'] === 'url')
                                        <a href="{{ $answer['value'] }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-500">
                                            {{ $answer['value'] }}
                                            <svg class="inline w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @elseif($answer['type'] === 'textarea')
                                        <div class="whitespace-pre-line">{{ $answer['value'] }}</div>
                                    @elseif($answer['type'] === 'file')
                                        @php
                                            $filePath = is_array($answer['value']) ? $answer['value'][0] : $answer['value'];
                                        @endphp
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">
                                            Ver imagen
                                        </a>
                                    @elseif(is_array($answer['value']))
                                        {{ implode(', ', $answer['value']) }}
                                    @else
                                        {{ $answer['value'] ?: 'Not provided' }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No responses found</h3>
                        <p class="mt-1 text-sm text-gray-500">This submission doesn't contain any responses.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Raw Data (for debugging - only show if needed) -->
        @if(config('app.debug') && !empty($submission['answers']))
            <div class="mt-6 bg-gray-50 shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Raw Submission Data</h3>
                    <p class="text-sm text-gray-500 mt-1">Debug information (only visible in debug mode)</p>
                </div>
                <div class="px-6 py-4">
                    <pre class="text-xs text-gray-600 bg-white p-4 rounded border overflow-x-auto">{{ json_encode($submission['answers'], JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif
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