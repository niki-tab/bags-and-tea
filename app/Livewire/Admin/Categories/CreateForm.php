<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Illuminate\Support\Str;

class CreateForm extends Component
{
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
    
    // Available parent categories
    public $parentCategories = [];

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_es' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255|unique:categories,slug',
        'slug_es' => 'required|string|max:255|unique:categories,slug',
        'description_1_en' => 'nullable|string|max:1000',
        'description_1_es' => 'nullable|string|max:1000',
        'description_2_en' => 'nullable|string|max:50000',
        'description_2_es' => 'nullable|string|max:50000',
        'meta_title_en' => 'nullable|string|max:255',
        'meta_title_es' => 'nullable|string|max:255',
        'meta_description_en' => 'nullable|string|max:1000',
        'meta_description_es' => 'nullable|string|max:1000',
        'parent_id' => 'nullable|exists:categories,id',
        'display_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadParentCategories();
        $this->setDefaultDisplayOrder();
    }

    public function loadParentCategories()
    {
        $this->parentCategories = CategoryEloquentModel::whereNull('parent_id')
            ->orderBy('display_order')
            ->get();
    }

    public function setDefaultDisplayOrder()
    {
        $maxOrder = CategoryEloquentModel::max('display_order');
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

        $categoryData = [
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
        ];

        $category = CategoryEloquentModel::create($categoryData);

        session()->flash('success', 'Category created successfully!');
        
        // Redirect to edit form to allow further editing
        return redirect()->route('admin.categories.edit', $category->id);
    }

    public function render()
    {
        return view('livewire.admin.categories.create-form');
    }
}