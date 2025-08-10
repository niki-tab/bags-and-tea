<?php

namespace Src\Admin\Order\Fees\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Order\Fees\Infrastructure\Eloquent\MarketplaceFeeEloquentModel;

class MarketplaceFeeManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $fee_name_en = '';
    public $fee_name_es = '';
    public $fee_code = '';
    public $description = '';
    public $fee_type = 'fixed';
    public $fixed_amount = 0;
    public $percentage_rate = 0;
    public $tiered_rates = null;
    public $minimum_order_amount = null;
    public $maximum_fee_amount = null;
    public $applicable_countries = null;
    public $applicable_payment_methods = null;
    public $is_active = true;
    public $effective_from = null;
    public $effective_until = null;
    public $visible_to_customer = true;
    public $customer_label = '';
    public $display_order = 0;

    public $search = '';
    public $filterFeeType = '';
    public $filterActive = '';

    public function getRules()
    {
        $rules = [
            'fee_name_en' => 'required|string|max:255',
            'fee_name_es' => 'required|string|max:255',
            'fee_code' => 'required|string|max:255|unique:marketplace_fees,fee_code',
            'description' => 'nullable|string',
            'fee_type' => 'required|in:fixed,percentage,tiered',
            'fixed_amount' => 'nullable|numeric|min:0',
            'percentage_rate' => 'nullable|numeric|min:0|max:100',
            'tiered_rates' => 'nullable|string', // Changed from json to string for easier handling
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'maximum_fee_amount' => 'nullable|numeric|min:0',
            'applicable_countries' => 'nullable|string', // Changed from json to string
            'applicable_payment_methods' => 'nullable|string', // Changed from json to string
            'is_active' => 'boolean',
            'effective_from' => 'nullable|date',
            'effective_until' => 'nullable|date|after:effective_from',
            'visible_to_customer' => 'boolean',
            'customer_label' => 'nullable|string|max:255',
            'display_order' => 'required|integer|min:0',
        ];

        // Update validation rule for editing
        if ($this->editingId) {
            $rules['fee_code'] = 'required|string|max:255|unique:marketplace_fees,fee_code,' . $this->editingId;
        }

        return $rules;
    }

    public function render()
    {
        $marketplaceFees = MarketplaceFeeEloquentModel::query()
            ->when(!empty($this->search), function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('fee_name', 'like', '%' . $this->search . '%')
                             ->orWhere('fee_code', 'like', '%' . $this->search . '%')
                             ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!empty($this->filterFeeType), function ($query) {
                $query->where('fee_type', $this->filterFeeType);
            })
            ->when($this->filterActive !== '', function ($query) {
                $query->where('is_active', (bool) $this->filterActive);
            })
            ->orderBy('display_order')
            ->orderBy('fee_name')
            ->paginate(20);

        return view('pages.admin-panel.dashboard.marketplace-fees.index', [
            'marketplaceFees' => $marketplaceFees
        ]);
    }

    public function create()
    {
        $this->reset([
            'editingId', 'fee_name_en', 'fee_name_es', 'fee_code', 'description', 'fee_type',
            'fixed_amount', 'percentage_rate', 'tiered_rates', 'minimum_order_amount',
            'maximum_fee_amount', 'applicable_countries', 'applicable_payment_methods',
            'is_active', 'effective_from', 'effective_until', 'visible_to_customer',
            'customer_label', 'display_order'
        ]);
        $this->is_active = true;
        $this->visible_to_customer = true;
        $this->display_order = 0;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $fee = MarketplaceFeeEloquentModel::findOrFail($id);
        
        
        $this->editingId = $id;
        
        // Handle translatable fee_name - get both English and Spanish
        // Get the raw JSON data from the database instead of the translated value
        $rawFeeNameData = $fee->getOriginal('fee_name');
        
        if ($rawFeeNameData) {
            // If it's JSON string, decode it
            if (is_string($rawFeeNameData)) {
                $feeNameArray = json_decode($rawFeeNameData, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($feeNameArray)) {
                    $this->fee_name_en = $feeNameArray['en'] ?? '';
                    $this->fee_name_es = $feeNameArray['es'] ?? '';
                } else {
                    // Fallback - use the string for both languages
                    $this->fee_name_en = $rawFeeNameData;
                    $this->fee_name_es = $rawFeeNameData;
                }
            } elseif (is_array($rawFeeNameData)) {
                // If it's already an array
                $this->fee_name_en = $rawFeeNameData['en'] ?? '';
                $this->fee_name_es = $rawFeeNameData['es'] ?? '';
            }
        } else {
            // Fallback to using Spatie translatable methods
            $this->fee_name_en = $fee->getTranslation('fee_name', 'en') ?? '';
            $this->fee_name_es = $fee->getTranslation('fee_name', 'es') ?? '';
        }
        
        $this->fee_code = $fee->fee_code;
        $this->description = $fee->description;
        $this->fee_type = $fee->fee_type;
        $this->fixed_amount = $fee->fixed_amount;
        $this->percentage_rate = $fee->percentage_rate;
        $this->tiered_rates = $fee->tiered_rates ? json_encode($fee->tiered_rates) : null;
        $this->minimum_order_amount = $fee->minimum_order_amount;
        $this->maximum_fee_amount = $fee->maximum_fee_amount;
        $this->applicable_countries = $fee->applicable_countries ? json_encode($fee->applicable_countries) : null;
        $this->applicable_payment_methods = $fee->applicable_payment_methods ? json_encode($fee->applicable_payment_methods) : null;
        $this->is_active = $fee->is_active;
        $this->effective_from = $fee->effective_from ? $fee->effective_from->format('Y-m-d') : null;
        $this->effective_until = $fee->effective_until ? $fee->effective_until->format('Y-m-d') : null;
        $this->visible_to_customer = $fee->visible_to_customer;
        $this->customer_label = $fee->customer_label;
        $this->display_order = $fee->display_order;
        
        $this->showModal = true;
    }

    public function save()
    {
        try {

            $this->validate($this->getRules());

            // Helper function to safely decode JSON
            $decodeJson = function($jsonString) {
                if (empty($jsonString) || trim($jsonString) === '') {
                    return null;
                }
                $decoded = json_decode($jsonString, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
                }
                return $decoded;
            };

            $data = [
                'fee_name' => [
                    'en' => $this->fee_name_en,
                    'es' => $this->fee_name_es,
                ],
                'fee_code' => $this->fee_code,
                'description' => $this->description,
                'fee_type' => $this->fee_type,
                'fixed_amount' => $this->fixed_amount ? (float)$this->fixed_amount : null,
                'percentage_rate' => $this->percentage_rate ? (float)$this->percentage_rate : null,
                'tiered_rates' => $decodeJson($this->tiered_rates),
                'minimum_order_amount' => $this->minimum_order_amount ? (float)$this->minimum_order_amount : null,
                'maximum_fee_amount' => $this->maximum_fee_amount ? (float)$this->maximum_fee_amount : null,
                'applicable_countries' => $decodeJson($this->applicable_countries),
                'applicable_payment_methods' => $decodeJson($this->applicable_payment_methods),
                'is_active' => (bool)$this->is_active,
                'effective_from' => $this->effective_from ?: null,
                'effective_until' => $this->effective_until ?: null,
                'visible_to_customer' => (bool)$this->visible_to_customer,
                'customer_label' => $this->customer_label,
                'display_order' => (int)$this->display_order,
            ];


            if ($this->editingId) {
                $fee = MarketplaceFeeEloquentModel::findOrFail($this->editingId);
                $fee->update($data);
                session()->flash('message', 'Marketplace fee updated successfully.');
            } else {
                $fee = MarketplaceFeeEloquentModel::create($data);
                session()->flash('message', 'Marketplace fee created successfully.');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Re-throw validation errors to display them properly
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving marketplace fee: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        MarketplaceFeeEloquentModel::findOrFail($id)->delete(); // Soft delete
        session()->flash('message', 'Marketplace fee deleted successfully.');
    }

    public function toggleActive($id)
    {
        $fee = MarketplaceFeeEloquentModel::findOrFail($id);
        $fee->update(['is_active' => !$fee->is_active]);
        session()->flash('message', 'Marketplace fee status updated.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset([
            'editingId', 'fee_name_en', 'fee_name_es', 'fee_code', 'description', 'fee_type',
            'fixed_amount', 'percentage_rate', 'tiered_rates', 'minimum_order_amount',
            'maximum_fee_amount', 'applicable_countries', 'applicable_payment_methods',
            'is_active', 'effective_from', 'effective_until', 'visible_to_customer',
            'customer_label', 'display_order'
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterFeeType()
    {
        $this->resetPage();
    }

    public function updatedFilterActive()
    {
        $this->resetPage();
    }

    public function getFeeTypes()
    {
        return [
            'fixed' => 'Fixed Amount',
            'percentage' => 'Percentage',
            'tiered' => 'Tiered',
        ];
    }

    public function getFeeTypeName($feeType)
    {
        $types = $this->getFeeTypes();
        return $types[$feeType] ?? $feeType;
    }

    public function getFormattedAmount($fee)
    {
        switch ($fee->fee_type) {
            case 'fixed':
                return '€' . number_format($fee->fixed_amount, 2);
            case 'percentage':
                return number_format($fee->percentage_rate, 2) . '%';
            case 'tiered':
                return 'Tiered';
            default:
                return '-';
        }
    }
}