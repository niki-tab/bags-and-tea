@extends('layouts.admin.app')

@section('page-title', 'Activity Log')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-bold text-gray-900">Activity Log</h2>
        <p class="mt-1 text-sm text-gray-600">Track all admin panel activity including logins, logouts, and data changes</p>
    </div>

    <!-- Filters -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.activity-log') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Search in description or properties..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-48">
                    <label for="log_name" class="block text-sm font-medium text-gray-700 mb-1">Log Type</label>
                    <select name="log_name" id="log_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        @foreach($logNames as $logName)
                            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('-', ' ', $logName)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Filter
                    </button>
                    <a href="{{ route('admin.activity-log') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date/Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP Address
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Details
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->created_at->format('Y-m-d H:i:s') }}
                                <div class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClass = match($activity->log_name) {
                                        'admin-auth' => 'bg-blue-100 text-blue-800',
                                        'admin-products' => 'bg-green-100 text-green-800',
                                        'admin-orders' => 'bg-yellow-100 text-yellow-800',
                                        'admin-users' => 'bg-purple-100 text-purple-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('admin-', '', $activity->log_name ?? 'default')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($activity->causer)
                                    {{ $activity->causer->name ?? $activity->causer->email ?? 'Unknown' }}
                                @else
                                    <span class="text-gray-400">System / Anonymous</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $activity->description }}
                                @if($activity->subject_type && $activity->subject_id)
                                    <div class="text-xs text-gray-500">
                                        {{ class_basename($activity->subject_type) }}: {{ Str::limit($activity->subject_id, 8) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->properties['ip_address'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($activity->properties && count($activity->properties) > 0)
                                    <button type="button"
                                        onclick="toggleDetails({{ $activity->id }})"
                                        class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </button>
                                    <div id="details-{{ $activity->id }}" class="hidden mt-2 p-2 bg-gray-50 rounded text-xs max-w-md overflow-auto">
                                        <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">No activity logs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function toggleDetails(id) {
    const element = document.getElementById('details-' + id);
    element.classList.toggle('hidden');
}
</script>
@endsection
