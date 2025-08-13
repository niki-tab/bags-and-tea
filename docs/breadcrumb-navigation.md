# Breadcrumb Navigation System

## Overview

This document describes the breadcrumb navigation system implemented for product detail pages, providing users with clear navigation paths and improved user experience.

## Feature Description

Breadcrumb navigation appears on product detail pages between the product title and product specifications, showing the hierarchical path: `Shop > [Category] > [Product Name]`.

## Implementation

### Backend Logic

#### Location
- **Component**: `src/Products/Product/Frontend/ProductDetail.php`
- **Method**: `setupBreadcrumbs()`

#### Breadcrumb Structure
The breadcrumb system generates a structured array with the following elements:

```php
$this->breadcrumbs = [
    [
        'text' => 'Shop' | 'Tienda',
        'url' => route('shop.show.es|en', ['locale' => $locale])
    ],
    [
        'text' => 'Category Name',
        'url' => route('shop.show.es|en', ['locale' => $locale, 'slug' => $categorySlug])
    ],
    [
        'text' => 'Product Name',
        'url' => null,
        'is_current' => true
    ]
];
```

#### Algorithm Implementation

```php
public function setupBreadcrumbs()
{
    $shopText = $this->lang === 'es' ? 'Tienda' : 'Shop';
    $shopRoute = $this->lang === 'es' ? 'shop.show.es' : 'shop.show.en';
    
    $this->breadcrumbs = [
        [
            'text' => $shopText,
            'url' => route($shopRoute, ['locale' => $this->lang])
        ]
    ];
    
    // Add bag category if product has categories
    if ($this->product && $this->product->categories->isNotEmpty()) {
        // Find the category that belongs to "Bolsos"/"Bags" parent category
        $bagCategory = null;
        
        foreach ($this->product->categories as $category) {
            if ($category->parent_id) {
                $parent = \Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel::find($category->parent_id);
                if ($parent) {
                    $parentNameEs = $parent->getTranslation('name', 'es');
                    $parentNameEn = $parent->getTranslation('name', 'en');
                    
                    // Look specifically for "Bolsos" (Spanish) or "Bags" (English) parent category
                    if ($parentNameEs === 'Bolsos' || $parentNameEn === 'Bags') {
                        $bagCategory = $category;
                        break;
                    }
                }
            }
        }
        
        // If we found a bag category, add it to breadcrumbs
        if ($bagCategory) {
            $categorySlug = $bagCategory->getTranslation('slug', $this->lang);
            $categoryRoute = $this->lang === 'es' ? 'shop.show.es' : 'shop.show.en';
            
            $this->breadcrumbs[] = [
                'text' => $bagCategory->getTranslation('name', $this->lang),
                'url' => route($categoryRoute, ['locale' => $this->lang, 'slug' => $categorySlug])
            ];
        }
    }
    
    // Add product name as final breadcrumb (no link, just text)
    $this->breadcrumbs[] = [
        'text' => $this->product->getTranslation('name', $this->lang),
        'url' => null,
        'is_current' => true
    ];
}
```

#### Smart Category Selection
The system intelligently selects the correct category:

1. **Filters Product Categories**: Looks through all categories assigned to the product
2. **Identifies Bag Categories**: Finds categories whose parent is "Bolsos" (Spanish) or "Bags" (English)
3. **Selects Child Category**: Uses the specific bag type (e.g., "Bolsos Louis Vuitton") rather than the generic parent
4. **Ignores Non-Bag Categories**: Skips categories like "Material" or "Color" that don't represent bag types

### Frontend Display

#### Location
- **Template**: `resources/views/livewire/products/product/show.blade.php`
- **Position**: Between product title and specifications

#### HTML Structure

```blade
@if(!empty($breadcrumbs))
    <nav class="flex justify-center mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach($breadcrumbs as $index => $breadcrumb)
                <li class="flex items-center">
                    @if($index > 0)
                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    
                    @if($breadcrumb['url'] && !($breadcrumb['is_current'] ?? false))
                        <a href="{{ $breadcrumb['url'] }}" class="text-color-2 hover:text-[#AC2231] transition-colors">
                            {{ $breadcrumb['text'] }}
                        </a>
                    @else
                        <span class="text-color-2">
                            {{ $breadcrumb['text'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
```

#### Visual Design
- **Layout**: Centered horizontally on the page
- **Separators**: Right-pointing arrow icons between breadcrumb items
- **Colors**: Uses theme colors (`text-color-2`, `#AC2231` for hover)
- **Responsiveness**: Adapts to mobile and desktop screens
- **Typography**: Small text size for subtle navigation aid

## Features

### 1. Multilingual Support
- **Spanish**: "Tienda > Bolsos Louis Vuitton > Nombre del Bolso"
- **English**: "Shop > Louis Vuitton Bags > Bag Name"
- Automatically detects current locale and displays appropriate text

### 2. Smart Link Generation
- **Shop Link**: Always clickable, redirects to main shop page
- **Category Link**: Clickable, redirects to filtered shop page for that category
- **Product Name**: Not clickable (current page indicator)

### 3. Route Handling
- Uses proper localized routes (`shop.show.es`, `shop.show.en`)
- Includes required `locale` parameter for all routes
- Category links include `slug` parameter for filtering

### 4. Category Intelligence
- Identifies the correct bag category from multiple product categories
- Prioritizes child categories over parent categories for specificity
- Handles complex category hierarchies correctly

## Examples

### Spanish Product Page
```
Tienda > Bolsos Louis Vuitton > Bolso Speedy 25
```

### English Product Page  
```
Shop > Louis Vuitton Bags > Speedy 25 Bag
```

### Product with Multiple Categories
For a product with categories: ["Bolsos Louis Vuitton", "Cuero", "Negro"]
- Shows: `Shop > Bolsos Louis Vuitton > Product Name`
- Ignores: "Cuero" (Material) and "Negro" (Color) categories

## Technical Requirements

### Database Dependencies
- Products must have categories assigned via `product_category` pivot table
- Categories must have proper parent-child relationships
- Category translations must be properly configured

### Route Dependencies
- `shop.show.es` and `shop.show.en` routes must exist
- Routes must accept `locale` and optional `slug` parameters
- Category filtering must be implemented in shop page

## User Experience Benefits

1. **Clear Navigation**: Users understand where they are in the site hierarchy
2. **Easy Backtracking**: One-click return to shop or category pages
3. **Improved SEO**: Search engines better understand page relationships
4. **Mobile Friendly**: Compact design works well on small screens
5. **Accessibility**: Semantic HTML structure supports screen readers

## Future Enhancements

- Add structured data markup for SEO
- Implement keyboard navigation
- Add breadcrumb history for back/forward functionality
- Consider adding brand-level breadcrumbs for more detailed navigation