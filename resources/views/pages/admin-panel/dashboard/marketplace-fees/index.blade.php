<div>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Marketplace Fees Management</h1>
                <p class="mt-1 text-sm text-gray-500">Manage marketplace fees and pricing structure</p>
            </div>
            <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Marketplace Fee
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, code, or description..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee Type</label>
                <select wire:model.live="filterFeeType" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="fixed">Fixed</option>
                    <option value="percentage">Percentage</option>
                    <option value="tiered">Tiered</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="filterActive" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="$set('search', ''); $set('filterFeeType', ''); $set('filterActive', '')" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Marketplace Fees Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Visible</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($marketplaceFees as $fee)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex flex-col">
                                    <span class="font-medium">{{ $fee->getTranslation('fee_name', 'en') }}</span>
                                    @if($fee->description)
                                        <span class="text-xs text-gray-500">{{ Str::limit($fee->description, 50) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $fee->fee_code }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $this->getFeeTypeName($fee->fee_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $this->getFormattedAmount($fee) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $fee->visible_to_customer ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $fee->visible_to_customer ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $fee->display_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleActive('{{ $fee->id }}')" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $fee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $fee->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="edit('{{ $fee->id }}')" class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </button>
                                    <button wire:click="delete('{{ $fee->id }}')" onclick="return confirm('Are you sure you want to delete this marketplace fee? This will soft delete it to preserve order history.')" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No marketplace fees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $marketplaceFees->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
                <div class="max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $editingId ? 'Edit Marketplace Fee' : 'Create Marketplace Fee' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <form wire:submit.prevent="save" class="space-y-6">
                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fee Name (English) *</label>
                                    <input type="text" wire:model="fee_name_en" placeholder="Buyer Protection Fee" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('fee_name_en') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fee Name (Spanish) *</label>
                                    <input type="text" wire:model="fee_name_es" placeholder="Tarifa de Protección del Comprador" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('fee_name_es') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fee Code *</label>
                                <input type="text" wire:model="fee_code" placeholder="buyer_protection" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fee_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for this fee (lowercase, underscores allowed)</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="description" rows="3" placeholder="Fee description for internal reference" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <!-- Fee Calculation -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Fee Calculation</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fee Type *</label>
                                        <select wire:model="fee_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="tiered">Tiered</option>
                                        </select>
                                        @error('fee_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    @if($fee_type === 'fixed')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fixed Amount (€)</label>
                                            <input type="number" step="0.01" wire:model="fixed_amount" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('fixed_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                    
                                    @if($fee_type === 'percentage')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Percentage Rate (%)</label>
                                            <input type="number" step="0.01" wire:model="percentage_rate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('percentage_rate') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    @endif
                                    
                                    @if($fee_type === 'tiered')
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tiered Rates (JSON)</label>
                                            <textarea wire:model="tiered_rates" rows="3" placeholder='{"0-50": 2.50, "50-100": 5.00, "100+": 10.00}' class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            @error('tiered_rates') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            <p class="mt-1 text-xs text-gray-500">JSON format for tiered fee structure</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Application Rules -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Application Rules</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount (€)</label>
                                        <input type="number" step="0.01" wire:model="minimum_order_amount" placeholder="0.00" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('minimum_order_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">Apply fee only if order total exceeds this amount</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Fee Amount (€)</label>
                                        <input type="number" step="0.01" wire:model="maximum_fee_amount" placeholder="No limit" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('maximum_fee_amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">Cap the fee at this maximum amount</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Applicable Countries (JSON)</label>
                                        <textarea wire:model="applicable_countries" rows="2" placeholder='["ES", "FR", "DE"]' class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        @error('applicable_countries') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">Leave empty to apply to all countries</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Applicable Payment Methods (JSON)</label>
                                        <textarea wire:model="applicable_payment_methods" rows="2" placeholder='["card", "paypal"]' class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        @error('applicable_payment_methods') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">Leave empty to apply to all payment methods</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Effective Dates -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Effective Dates</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Effective From</label>
                                        <input type="date" wire:model="effective_from" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('effective_from') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Effective Until</label>
                                        <input type="date" wire:model="effective_until" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('effective_until') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Display Settings -->
                            <div class="border-t pt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Display Settings</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Label</label>
                                        <input type="text" wire:model="customer_label" placeholder="Buyer Protection" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('customer_label') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">How this fee appears to customers</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                                        <input type="number" wire:model="display_order" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('display_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                                    </div>
                                </div>

                                <div class="mt-4 space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="visible_to_customer" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <label class="ml-2 block text-sm text-gray-700">Visible to Customer</label>
                                        <span class="ml-2 text-xs text-gray-500">Show this fee in checkout breakdown</span>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <label class="ml-2 block text-sm text-gray-700">Active</label>
                                        <span class="ml-2 text-xs text-gray-500">Fee is applied to new orders</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Cancel
                        </button>
                        <button type="button" wire:click="save" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            {{ $editingId ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>