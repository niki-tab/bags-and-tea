# Product Card Component Documentation

## Overview
This document describes the reusable product card component system implemented for consistent product display across the platform.

## Component Implementation

### Livewire Product Card Component
- **Class**: `src/Products/Product/Frontend/ProductCard.php`
- **View**: `resources/views/livewire/products/product-card.blade.php`
- **Registration**: `app/Providers/AppServiceProvider.php`

### Features
1. **Image Carousel**: Multiple product images with navigation
2. **Responsive Design**: Mobile and desktop optimized
3. **Sold Out Banner**: Diagonal overlay for unavailable products
4. **Brand Display**: Product brand with translations
5. **Price Formatting**: Proper currency formatting
6. **Product Links**: Direct links to product detail pages

## Technical Implementation

### Product Card Component Class
```php
<?php
namespace Src\Products\Product\Frontend;

use Livewire\Component;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class ProductCard extends Component
{
    public ProductEloquentModel $product;

    public function mount(ProductEloquentModel $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.products.product-card');
    }
}
```

### Image Carousel Features
- **Multiple Images**: Supports unlimited product images
- **Navigation Arrows**: Previous/Next buttons with custom styling
- **Image Indicators**: Dot navigation for image selection
- **Responsive Images**: Proper scaling for different screen sizes
- **Fallback**: Placeholder icon when no images available

### Styling Customizations
- **Arrow Colors**: Brand-consistent colors
  - Circle background: `#F8F3F0` (light beige)
  - Arrow color: `text-color-2` (dark brown)
- **Card Design**: Clean, minimal appearance without rounded corners
- **Hover Effects**: Subtle shadow transitions

## Home Page Integration

### Controller Updates
- **File**: `app/Http/Controllers/HomeController.php`
- **Logic**: Fetch latest 9 in-stock products
- **Device Detection**: Show 8 products on mobile/tablet, 9 on desktop

```php
// Determine limit based on device (8 for mobile/tablet, 9 for desktop)
$isMobileOrTablet = request()->header('User-Agent') && preg_match('/(Mobile|Android|iPhone|iPad|Tablet)/', request()->header('User-Agent'));
$limit = $isMobileOrTablet ? 8 : 9;

$featuredProducts = ProductEloquentModel::where('out_of_stock', false)
    ->where('is_sold_out', false)
    ->with(['brand', 'media'])
    ->orderBy('created_at', 'desc')
    ->limit($limit)
    ->get();
```

### Template Integration
- **File**: `resources/views/home.blade.php`
- **Usage**: `@livewire('products.product-card', ['product' => $product], key($product->id))`
- **Grid Layout**: Responsive 2-column (mobile) to 3-column (desktop) grid

## Key Insights & Solutions

### Device-Specific Product Count
**Insight**: Different screen sizes need different product counts for optimal layout
- **Mobile/Tablet**: 8 products (2×4 grid)
- **Desktop**: 9 products (3×3 grid)
- **Detection**: User-Agent header parsing for device identification

### Image Carousel Optimization
**Insight**: Product images need sophisticated navigation for better UX
- **R2 Cloud Storage**: Support for both local and cloud storage paths
- **Image Processing**: Automatic path resolution for different storage types
- **Performance**: Lazy loading and proper image optimization

### Component Reusability
**Insight**: Consistent product display requires shared components
- **DRY Principle**: Single component used across shop and home pages
- **Maintainability**: Changes in one place affect all product displays
- **Consistency**: Same look and feel everywhere

### Email Client Compatibility
**Insight**: Different approaches needed for web vs email display
- **Web Components**: Alpine.js and modern CSS features
- **Email Templates**: Table-based layouts with inline styles
- **Separation**: Distinct approaches for different contexts

## Styling Details

### Arrow Button Styling
```css
.arrow-button {
    background-color: #F8F3F0;
    color: var(--color-2);
    hover: background-color: #F8F3F0/80;
    border-radius: 50%;
    padding: 8px;
}
```

### Card Structure
- **No Rounded Corners**: Clean, sharp edges for modern look
- **Shadow Effects**: Subtle hover shadows for interactivity
- **Responsive Spacing**: Adaptive padding and margins

### Image Handling
- **Aspect Ratio**: Maintains product image proportions
- **Object Fit**: `object-contain` for proper image display
- **Fallback Icons**: SVG icons for missing images

## Component Registration
```php
// In AppServiceProvider.php
Livewire::component('products.product-card', \Src\Products\Product\Frontend\ProductCard::class);
```

## Usage Examples

### Home Page Usage
```blade
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 lg:gap-10">
    @foreach($featuredProducts as $product)
        @livewire('products.product-card', ['product' => $product], key($product->id))
    @endforeach
</div>
```

### Shop Page Integration
- **Note**: Shop page maintains its existing implementation
- **Future**: Could be migrated to use the same component
- **Consistency**: Same visual appearance achieved

## Performance Considerations

### Database Queries
- **Eager Loading**: Preload `brand` and `media` relationships
- **Filtering**: Database-level filtering for in-stock products
- **Ordering**: Sort by creation date for "latest products"

### Frontend Optimization
- **Alpine.js**: Lightweight JavaScript for carousel functionality
- **CSS Optimization**: Minimal CSS with utility classes
- **Image Loading**: Proper image optimization and loading

## Future Enhancements
1. **Wishlist Integration**: Add to wishlist functionality
2. **Quick View**: Modal with product details
3. **Comparison Tool**: Compare multiple products
4. **Stock Indicators**: Show quantity remaining
5. **Price History**: Display price changes
6. **Review Stars**: Show product ratings
7. **Badge System**: New/Sale/Popular badges

## Maintenance Notes
- **Shop Compatibility**: Existing shop functionality preserved
- **Testing**: Component tested in isolation and integration
- **Documentation**: Well-documented for future developers
- **Extensibility**: Easy to add new features or modify appearance