<?php

namespace Src\Admin\Blog\Categories\Frontend;

use Livewire\Component;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;
use Illuminate\Support\Str;

class EditBlogCategoryForm extends Component
{
    public $categoryId;
    public $category;
    
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
    public $color = '';
    
    // Available parent categories
    public $parentCategories = [];

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_es' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255',
        'slug_es' => 'required|string|max:255',
        'description_1_en' => 'nullable|string|max:1000',
        'description_1_es' => 'nullable|string|max:1000',
        'description_2_en' => 'nullable|string|max:1000',
        'description_2_es' => 'nullable|string|max:1000',
        'parent_id' => 'nullable|exists:blog_categories,id',
        'display_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
        'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
    ];

    public function mount($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->loadCategory();
        $this->loadParentCategories();
    }

    public function loadCategory()
    {
        $this->category = BlogCategoryEloquentModel::findOrFail($this->categoryId);
        
        // Load translations
        $this->name_en = $this->category->getTranslation('name', 'en');
        $this->name_es = $this->category->getTranslation('name', 'es');
        $this->slug_en = $this->category->getTranslation('slug', 'en');
        $this->slug_es = $this->category->getTranslation('slug', 'es');
        $this->description_1_en = $this->category->getTranslation('description_1', 'en') ?? '';
        $this->description_1_es = $this->category->getTranslation('description_1', 'es') ?? '';
        $this->description_2_en = $this->category->getTranslation('description_2', 'en') ?? '';
        $this->description_2_es = $this->category->getTranslation('description_2', 'es') ?? '';
        
        // Load other fields
        $this->parent_id = $this->category->parent_id ?? '';
        $this->display_order = $this->category->display_order;
        $this->is_active = $this->category->is_active;
        $this->color = $this->category->color ?? '';
    }

    public function loadParentCategories()
    {
        $this->parentCategories = BlogCategoryEloquentModel::where('id', '!=', $this->categoryId)
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

        $this->category->update([
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
            'color' => $this->color ?: null,
        ]);

        session()->flash('success', 'Blog category updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.blog.categories.edit-form');
    }
}