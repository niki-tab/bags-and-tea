<?php

namespace App\Livewire\Admin\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductMediaModel;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;

class ProductForm extends Component
{
    use WithFileUploads;

    public $productId;
    public $product;
    public $isEditing = false;
    
    // English fields
    public $name_en = '';
    public $slug_en = '';
    public $description_1_en = '';
    public $description_2_en = '';
    public $origin_country = '';
    public $status = "approved";
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
    public $brand_id = '';
    public $vendor_id = '';
    public $quality_id = '';
    public $product_type = 'simple';
    public $price = '';
    public $discounted_price = '';
    public $deal_price = '';
    public $sell_mode = '';
    public $sell_mode_quantity = '';
    public $stock = 1;
    public $stock_unit = '';
    public $out_of_stock = false;
    public $is_sold_out = false;
    public $is_hidden = false;
    public $featured = false;
    public $featured_position = 0;
    
    // Categories and attributes
    public $selectedCategories = [];
    public $selectedAttributes = [];
    
    // Media
    public $media = [];
    public $existingMedia = [];
    public $mediaToDelete = [];
    
    // Add this property to make Livewire track existingMedia properly
    protected $listeners = ['media-removed' => 'refreshComponent'];
    
    // Available options
    public $brands = [];
    public $vendors = [];
    public $qualities = [];
    public $categories = [];
    public $categoriesByParent = [];
    public $availableAttributes = [];
    public $attributesByParent = [];
    

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_es' => 'required|string|max:255',
        'slug_en' => 'required|string|max:255|unique:products,slug',
        'slug_es' => 'required|string|max:255|unique:products,slug',
        'description_1_en' => 'nullable|string|max:2000',
        'description_1_es' => 'nullable|string|max:2000',
        'description_2_en' => 'nullable|string|max:2000',
        'description_2_es' => 'nullable|string|max:2000',
        'origin_country' => 'nullable|string|max:255',
        'meta_title_en' => 'nullable|string|max:255',
        'meta_title_es' => 'nullable|string|max:255',
        'meta_description_en' => 'nullable|string|max:255',
        'meta_description_es' => 'nullable|string|max:255',
        'brand_id' => 'required|exists:brands,id',
        'vendor_id' => 'nullable|exists:vendors,id',
        'quality_id' => 'nullable|exists:qualities,id',
        'product_type' => 'required|string|max:100',
        'status' => 'required|string|max:100',
        'price' => 'required|numeric|min:0',
        'discounted_price' => 'nullable|numeric|min:0',
        'deal_price' => 'nullable|numeric|min:0',
        //'sell_mode' => 'required|string|max:50',
        //'sell_mode_quantity' => 'required|string|max:50',
        'stock' => 'required|integer|max:50',
        //'stock_unit' => 'required|string|max:50',
        'out_of_stock' => 'boolean',
        'is_sold_out' => 'boolean',
        'is_hidden' => 'boolean',
        'featured' => 'boolean',
        'featured_position' => 'integer|min:0',
        'media.*' => 'image|max:10240', // 10MB max
        'selectedCategories' => 'array',
        'selectedAttributes' => 'array',
    ];

    public function mount($productId = null)
    {
        $this->productId = $productId;
        $this->isEditing = !is_null($productId);
        
        $this->loadOptions();
        
        if ($this->isEditing) {
            $this->loadProduct();
        } else {
            $this->setDefaultVendor();
        }
    }

    public function loadProduct()
    {
        $this->product = ProductEloquentModel::with(['categories', 'attributes', 'media'])
            ->findOrFail($this->productId);
        
        // Load translations
        $this->name_en = $this->product->getTranslation('name', 'en');
        $this->name_es = $this->product->getTranslation('name', 'es');
        $this->slug_en = $this->product->getTranslation('slug', 'en');
        $this->slug_es = $this->product->getTranslation('slug', 'es');
        $this->description_1_en = $this->product->getTranslation('description_1', 'en');
        $this->description_1_es = $this->product->getTranslation('description_1', 'es');
        $this->description_2_en = $this->product->getTranslation('description_2', 'en');
        $this->description_2_es = $this->product->getTranslation('description_2', 'es');
        $this->meta_title_en = $this->product->getTranslation('meta_title', 'en');
        $this->meta_title_es = $this->product->getTranslation('meta_title', 'es');
        $this->meta_description_en = $this->product->getTranslation('meta_description', 'en');
        $this->meta_description_es = $this->product->getTranslation('meta_description', 'es');
        
        // Load other fields
        $this->brand_id = $this->product->brand_id;
        $this->vendor_id = $this->product->vendor_id;
        $this->quality_id = $this->product->quality_id;
        $this->origin_country = $this->product->origin_country;
        $this->product_type = $this->product->product_type;
        $this->status = $this->product->status;
        $this->price = $this->product->price;
        $this->discounted_price = $this->product->discounted_price;
        $this->deal_price = $this->product->deal_price;
        $this->sell_mode = $this->product->sell_mode;
        $this->sell_mode_quantity = $this->product->sell_mode_quantity;
        $this->stock = $this->product->stock;
        $this->stock_unit = $this->product->stock_unit;
        $this->out_of_stock = $this->product->out_of_stock;
        $this->is_sold_out = $this->product->is_sold_out;
        $this->is_hidden = $this->product->is_hidden;
        $this->featured = $this->product->featured;
        $this->featured_position = $this->product->featured_position;
        
        // Load relationships
        $this->selectedCategories = $this->product->categories->pluck('id')->toArray();
        $this->selectedAttributes = $this->product->attributes->pluck('id')->toArray();
        $this->existingMedia = $this->product->media->toArray();
        
        // Update validation rules for editing
        $this->rules['slug_en'] = 'required|string|max:255|unique:products,slug,' . $this->productId . ',id';
        $this->rules['slug_es'] = 'required|string|max:255|unique:products,slug,' . $this->productId . ',id';
    }

    public function loadOptions()
    {
        $this->brands = BrandEloquentModel::orderBy('name')->get();
        $this->vendors = VendorEloquentModel::with('user')->orderBy('business_name')->get();
        $this->qualities = QualityEloquentModel::orderBy('display_order')->get();
        $this->categories = CategoryEloquentModel::with('parent')->orderBy('display_order')->get();
        $this->availableAttributes = AttributeEloquentModel::with('parent')->orderBy('display_order')->get();
        
        // Group categories by parent
        $this->categoriesByParent = $this->groupByParent($this->categories);
        
        // Group attributes by parent
        $this->attributesByParent = $this->groupByParent($this->availableAttributes);
    }

    private function groupByParent($items)
    {
        $grouped = [];
        
        foreach ($items as $item) {
            // Skip root items (items without parent_id)
            if (!$item->parent_id) {
                continue;
            }
            
            $parentKey = $item->parent_id;
            
            if (!isset($grouped[$parentKey])) {
                $parentName = $item->parent ? $item->parent->name : 'Unknown Parent';
                
                $grouped[$parentKey] = [
                    'parent_name' => $parentName,
                    'children' => []
                ];
            }
            
            $grouped[$parentKey]['children'][] = $item;
        }
        
        return $grouped;
    }

    public function setDefaultVendor()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('vendor')) {
            $vendor = VendorEloquentModel::where('user_id', $user->id)->first();
            if ($vendor) {
                $this->vendor_id = $vendor->id;
            }
        }
    }

    public function updatedNameEn()
    {
        $this->slug_en = Str::slug($this->name_en);
    }

    public function updatedNameEs()
    {
        $this->slug_es = Str::slug($this->name_es);
    }


    public function removeMedia($mediaId)
    {
        // Add to deletion queue
        $this->mediaToDelete[] = $mediaId;
        
        // Remove from existing media array
        $newMedia = [];
        foreach ($this->existingMedia as $media) {
            if ($media['id'] !== $mediaId) {
                $newMedia[] = $media;
            }
        }
        $this->existingMedia = $newMedia;
        
        // Force Livewire to re-render by dispatching an event
        $this->dispatch('media-removed', $mediaId);
        
        // Add a flash message to confirm removal
        $this->addError('media_removed', 'Image marked for removal. Save the form to permanently delete.');
    }

    public function setPrimaryMedia($mediaId)
    {
        // Update the existing media array to set new primary
        foreach ($this->existingMedia as &$media) {
            $media['is_primary'] = $media['id'] === $mediaId;
        }
        
        // Force Livewire to re-render the component
    }
    
    public function refreshComponent()
    {
        // This method will be called when the media-removed event is dispatched
        // It forces Livewire to re-render the component
    }


    public function save()
    {
        $this->validate();

        $productData = [
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
            'origin_country' => $this->origin_country,
            'meta_title' => [
                'en' => $this->meta_title_en,
                'es' => $this->meta_title_es,
            ],
            'meta_description' => [
                'en' => $this->meta_description_en,
                'es' => $this->meta_description_es,
            ],
            'brand_id' => $this->brand_id,
            'vendor_id' => $this->vendor_id ?: null,
            'quality_id' => $this->quality_id ?: null,
            'product_type' => $this->product_type,
            'status' => $this->status ?: "approved",
            'price' => $this->price,
            'discounted_price' => $this->discounted_price ?: null,
            'deal_price' => $this->deal_price ?: null,
            //'sell_mode' => $this->sell_mode,
            //'sell_mode_quantity' => $this->sell_mode_quantity,
            'stock' => $this->stock,
            //'stock_unit' => $this->stock_unit,
            'out_of_stock' => $this->out_of_stock,
            'is_sold_out' => $this->is_sold_out,
            'is_hidden' => $this->is_hidden,
            'featured' => $this->featured,
            'featured_position' => $this->featured_position,
        ];

        if ($this->isEditing) {
            $this->product->update($productData);
            $product = $this->product;
        } else {
            $productData['id'] = (string) Str::uuid();
            $product = ProductEloquentModel::create($productData);
        }

        // Handle categories
        $product->categories()->sync($this->selectedCategories);
        
        // Handle attributes
        $product->attributes()->sync($this->selectedAttributes);
        
        // Handle media deletion
        if (!empty($this->mediaToDelete)) {
            ProductMediaModel::whereIn('id', $this->mediaToDelete)->each(function($media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            });
            $this->mediaToDelete = []; // Clear the deletion queue
        }
        
        // Handle primary media updates
        foreach ($this->existingMedia as $mediaData) {
            ProductMediaModel::where('id', $mediaData['id'])->update([
                'is_primary' => $mediaData['is_primary']
            ]);
        }

        // Handle new media uploads
        if (!empty($this->media)) {
            $sortOrder = ProductMediaModel::where('product_id', $product->id)->max('sort_order') + 1;
            
            foreach ($this->media as $file) {
                $path = $file->store('products/' . $product->id, 'public');
                
                ProductMediaModel::create([
                    'id' => (string) Str::uuid(),
                    'product_id' => $product->id,
                    'file_path' => 'storage/' . $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => 'image',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'sort_order' => $sortOrder++,
                    'is_primary' => false,
                ]);
            }
            $this->media = []; // Clear the media array after upload
        }

        if ($this->isEditing) {
            // Reload the existing media to reflect changes
            $this->existingMedia = $this->product->fresh()->media->toArray();
            session()->flash('success', 'Product updated successfully!');
            // Stay on the same page when editing
        } else {
            session()->flash('success', 'Product created successfully!');
            // Redirect to edit page after creation
            return redirect()->route('admin.products.edit', $product->id);
        }
    }

    public function render()
    {
        return view('livewire.admin.products.product-form');
    }
}