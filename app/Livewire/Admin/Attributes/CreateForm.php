<?php

namespace App\Livewire\Admin\Attributes;

use Livewire\Component;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Illuminate\Support\Str;

class CreateForm extends Component
{
    // English fields
    public $name_en = '';
    public $slug_en = '';
    public $description_1_en = '';
    public $description_2_en = '';
    
    // Spanish fields
    public $name_es = '';
    public $slug_es = '';
    public $description_1_es = '';
    public $description_2_es = '';
    
    // Other fields
    public $parent_id = '';
    public $display_order = 0;
    public $is_active = true;
    
    // Available parent attributes
    public $parentAttributes = [];

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_es' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255|unique:attributes,slug',
        'slug_es' => 'required|string|max:255|unique:attributes,slug',
        'description_1_en' => 'nullable|string|max:1000',
        'description_1_es' => 'nullable|string|max:1000',
        'description_2_en' => 'nullable|string|max:1000',
        'description_2_es' => 'nullable|string|max:1000',
        'parent_id' => 'nullable|exists:attributes,id',
        'display_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadParentAttributes();
        $this->setDefaultDisplayOrder();
    }

    public function loadParentAttributes()
    {
        $this->parentAttributes = AttributeEloquentModel::whereNull('parent_id')
            ->orderBy('display_order')
            ->get();
    }

    public function setDefaultDisplayOrder()
    {
        $maxOrder = AttributeEloquentModel::max('display_order');
        $this->display_order = $maxOrder ? $maxOrder + 1 : 1;
    }

    public function updatedNameEn()
    {
        $this->slug_en = Str::slug($this->name_en);
    }

    public function updatedNameEs()
    {
        $this->slug_es = Str::slug($this->name_es);
    }

    public function save()
    {
        $this->validate();

        $attributeData = [
            'id' => (string) Str::uuid(),
            'name' => [
                'en' => $this->name_en,
                'es' => $this->name_es,
            ],
            'slug' => [
                'en' => $this->slug_en,
                'es' => $this->slug_es,
            ],
            'description_1' => [
                'en' => $this->description_1_en,
                'es' => $this->description_1_es,
            ],
            'description_2' => [
                'en' => $this->description_2_en,
                'es' => $this->description_2_es,
            ],
            'parent_id' => $this->parent_id ?: null,
            'display_order' => $this->display_order,
            'is_active' => $this->is_active,
        ];

        AttributeEloquentModel::create($attributeData);

        session()->flash('success', 'Attribute created successfully!');
        
        // Redirect to the attributes listing
        return redirect()->route('admin.attributes');
    }

    public function render()
    {
        return view('livewire.admin.attributes.create-form');
    }
}