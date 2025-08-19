<?php

namespace App\Livewire\Admin\Attributes;

use Livewire\Component;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Illuminate\Support\Str;

class EditForm extends Component
{
    public $attributeId;
    public $attribute;
    
    // English fields
    public $name_en = '';
    public $slug_en = '';
    public $description_1_en = '';
    public $description_2_en = '';
    public $meta_title_en = '';
    public $meta_description_en = '';
    
    // Spanish fields
    public $name_es = '';
    public $slug_es = '';
    public $description_1_es = '';
    public $description_2_es = '';
    public $meta_title_es = '';
    public $meta_description_es = '';
    
    // Other fields
    public $parent_id = '';
    public $display_order = 0;
    public $is_active = true;
    
    // Available parent attributes
    public $parentAttributes = [];

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_es' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255',
        'slug_es' => 'required|string|max:255',
        'description_1_en' => 'nullable|string|max:5000',
        'description_1_es' => 'nullable|string|max:5000',
        'description_2_en' => 'nullable|string|max:5000',
        'description_2_es' => 'nullable|string|max:5000',
        'meta_title_en' => 'nullable|string|max:1000',
        'meta_title_es' => 'nullable|string|max:1000',
        'meta_description_en' => 'nullable|string|max:5000',
        'meta_description_es' => 'nullable|string|max:5000',
        'parent_id' => 'nullable|exists:attributes,id',
        'display_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount($attributeId)
    {
        $this->attributeId = $attributeId;
        $this->loadAttribute();
        $this->loadParentAttributes();
    }

    public function loadAttribute()
    {
        $this->attribute = AttributeEloquentModel::findOrFail($this->attributeId);
        
        // Load translations
        $this->name_en = $this->attribute->getTranslation('name', 'en');
        $this->name_es = $this->attribute->getTranslation('name', 'es');
        $this->slug_en = $this->attribute->getTranslation('slug', 'en');
        $this->slug_es = $this->attribute->getTranslation('slug', 'es');
        $this->description_1_en = $this->attribute->getTranslation('description_1', 'en');
        $this->description_1_es = $this->attribute->getTranslation('description_1', 'es');
        $this->description_2_en = $this->attribute->getTranslation('description_2', 'en');
        $this->description_2_es = $this->attribute->getTranslation('description_2', 'es');
        $this->meta_title_en = $this->attribute->getTranslation('meta_title', 'en') ?? '';
        $this->meta_title_es = $this->attribute->getTranslation('meta_title', 'es') ?? '';
        $this->meta_description_en = $this->attribute->getTranslation('meta_description', 'en') ?? '';
        $this->meta_description_es = $this->attribute->getTranslation('meta_description', 'es') ?? '';
        
        // Load other fields
        $this->parent_id = $this->attribute->parent_id ?? '';
        $this->display_order = $this->attribute->display_order;
        $this->is_active = $this->attribute->is_active;
    }

    public function loadParentAttributes()
    {
        $this->parentAttributes = AttributeEloquentModel::where('id', '!=', $this->attributeId)
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get();
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

        $this->attribute->update([
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
            'meta_title' => [
                'en' => $this->meta_title_en,
                'es' => $this->meta_title_es,
            ],
            'meta_description' => [
                'en' => $this->meta_description_en,
                'es' => $this->meta_description_es,
            ],
            'parent_id' => $this->parent_id ?: null,
            'display_order' => $this->display_order,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Attribute updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.attributes.edit-form');
    }
}