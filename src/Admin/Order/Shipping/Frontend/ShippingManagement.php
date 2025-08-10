<?php

namespace Src\Admin\Order\Shipping\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Order\Shipping\Infrastructure\Eloquent\ShippingRateEloquentModel;

class ShippingManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $country_code = '';
    public $zone_name = '';
    public $rate_name = '';
    public $rate_type = 'fixed';
    public $base_rate = 0;
    public $per_kg_rate = 0;
    public $free_shipping_threshold = null;
    public $delivery_days_min = 3;
    public $delivery_days_max = 7;
    public $is_active = true;
    public $priority = 1;

    public $search = '';
    public $filterCountry = '';
    public $filterActive = '';

    protected $rules = [
        'country_code' => 'required|string|max:2',
        'zone_name' => 'nullable|string|max:255',
        'rate_name' => 'required|string|max:255',
        'rate_type' => 'required|in:fixed,weight_based,free,calculated',
        'base_rate' => 'required|numeric|min:0',
        'per_kg_rate' => 'nullable|numeric|min:0',
        'free_shipping_threshold' => 'nullable|numeric|min:0',
        'delivery_days_min' => 'required|integer|min:1',
        'delivery_days_max' => 'required|integer|min:1',
        'is_active' => 'boolean',
        'priority' => 'required|integer|min:0',
    ];

    public function render()
    {
        $shippingRates = ShippingRateEloquentModel::query()
            ->when(!empty($this->search), function ($query) {
                $search = strtoupper($this->search);
                $searchLower = strtolower($this->search);
                
                // Get country codes that match the search term
                $matchingCountryCodes = $this->getCountryCodesByName($searchLower);
                
                $query->where(function ($subQuery) use ($search, $matchingCountryCodes) {
                    $subQuery->where('country_code', 'like', '%' . $search . '%')
                             ->orWhere('rate_name', 'like', '%' . $this->search . '%')
                             ->orWhere('zone_name', 'like', '%' . $this->search . '%');
                    
                    // Also search by country names
                    if (!empty($matchingCountryCodes)) {
                        $subQuery->orWhereIn('country_code', $matchingCountryCodes);
                    }
                });
            })
            ->when(!empty($this->filterCountry), function ($query) {
                $query->where('country_code', 'like', '%' . strtoupper($this->filterCountry) . '%');
            })
            ->when($this->filterActive !== '', function ($query) {
                $query->where('is_active', (bool) $this->filterActive);
            })
            ->orderBy('priority')
            ->orderBy('country_code')
            ->paginate(20);

        return view('pages.admin-panel.dashboard.shipping.index', [
            'shippingRates' => $shippingRates
        ]);
    }

    public function create()
    {
        $this->reset([
            'editingId', 'country_code', 'zone_name', 'rate_name', 'rate_type',
            'base_rate', 'per_kg_rate', 'free_shipping_threshold', 
            'delivery_days_min', 'delivery_days_max', 'is_active', 'priority'
        ]);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $shippingRate = ShippingRateEloquentModel::findOrFail($id);
        
        $this->editingId = $id;
        $this->country_code = $shippingRate->country_code;
        $this->zone_name = $shippingRate->zone_name;
        $this->rate_name = $shippingRate->rate_name;
        $this->rate_type = $shippingRate->rate_type;
        $this->base_rate = $shippingRate->base_rate;
        $this->per_kg_rate = $shippingRate->per_kg_rate;
        $this->free_shipping_threshold = $shippingRate->free_shipping_threshold;
        $this->delivery_days_min = $shippingRate->delivery_days_min;
        $this->delivery_days_max = $shippingRate->delivery_days_max;
        $this->is_active = $shippingRate->is_active;
        $this->priority = $shippingRate->priority;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'country_code' => strtoupper($this->country_code),
            'zone_name' => $this->zone_name,
            'rate_name' => $this->rate_name,
            'rate_type' => $this->rate_type,
            'base_rate' => $this->base_rate,
            'per_kg_rate' => $this->per_kg_rate ?: 0,
            'free_shipping_threshold' => $this->free_shipping_threshold,
            'delivery_days_min' => $this->delivery_days_min,
            'delivery_days_max' => $this->delivery_days_max,
            'is_active' => $this->is_active,
            'priority' => $this->priority,
        ];

        if ($this->editingId) {
            ShippingRateEloquentModel::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Shipping rate updated successfully.');
        } else {
            ShippingRateEloquentModel::create($data);
            session()->flash('message', 'Shipping rate created successfully.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        ShippingRateEloquentModel::findOrFail($id)->delete();
        session()->flash('message', 'Shipping rate deleted successfully.');
    }

    public function toggleActive($id)
    {
        $shippingRate = ShippingRateEloquentModel::findOrFail($id);
        $shippingRate->update(['is_active' => !$shippingRate->is_active]);
        session()->flash('message', 'Shipping rate status updated.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset([
            'editingId', 'country_code', 'zone_name', 'rate_name', 'rate_type',
            'base_rate', 'per_kg_rate', 'free_shipping_threshold', 
            'delivery_days_min', 'delivery_days_max', 'is_active', 'priority'
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCountry()
    {
        $this->resetPage();
    }

    public function updatedFilterActive()
    {
        $this->resetPage();
    }

    private function getCountries()
    {
        return [
            'ES' => 'Spain',
            'FR' => 'France',
            'DE' => 'Germany',
            'IT' => 'Italy',
            'PT' => 'Portugal',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'AT' => 'Austria',
            'CH' => 'Switzerland',
            'GB' => 'United Kingdom',
            'IE' => 'Ireland',
            'SE' => 'Sweden',
            'DK' => 'Denmark',
            'NO' => 'Norway',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'CZ' => 'Czech Republic',
            'HU' => 'Hungary',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'HR' => 'Croatia',
            'RO' => 'Romania',
            'BG' => 'Bulgaria',
            'GR' => 'Greece',
            'CY' => 'Cyprus',
            'MT' => 'Malta',
            'LU' => 'Luxembourg',
            'EE' => 'Estonia',
            'LV' => 'Latvia',
            'LT' => 'Lithuania',
            'AD' => 'Andorra',
            'AL' => 'Albania',
            'BA' => 'Bosnia and Herzegovina',
            'BY' => 'Belarus',
            'FO' => 'Faroe Islands',
            'GI' => 'Gibraltar',
            'GL' => 'Greenland',
            'IS' => 'Iceland',
            'LI' => 'Liechtenstein',
            'MC' => 'Monaco',
            'MD' => 'Moldova',
            'ME' => 'Montenegro',
            'MK' => 'North Macedonia',
            'RS' => 'Serbia',
            'RU' => 'Russia',
            'SM' => 'San Marino',
            'UA' => 'Ukraine',
            'VA' => 'Vatican City',
            '*' => 'Worldwide (Default)',
        ];
    }

    public function getCountryName($countryCode)
    {
        $countries = $this->getCountries();
        return $countries[$countryCode] ?? $countryCode;
    }

    private function getCountryCodesByName($searchTerm)
    {
        $countries = $this->getCountries();
        $matchingCodes = [];
        $searchTerm = strtolower($searchTerm);

        foreach ($countries as $code => $name) {
            if (stripos(strtolower($name), $searchTerm) !== false) {
                $matchingCodes[] = $code;
            }
        }

        return $matchingCodes;
    }
}