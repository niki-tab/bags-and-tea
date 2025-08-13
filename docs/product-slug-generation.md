# Product Slug Generation System

## Overview

This document describes the unique slug generation system implemented for products in the admin panel, ensuring that all product slugs are unique to prevent database constraint violations.

## Problem Solved

Previously, when creating or editing products in the admin panel, if two products had similar names, they could generate identical slugs, leading to database unique constraint violations when attempting to save.

## Implementation

### Location
- **Component**: `app/Livewire/Admin/Products/ProductForm.php`
- **Methods**: `updatedNameEn()`, `updatedNameEs()`, `generateUniqueSlug()`, `slugExists()`

### How It Works

#### 1. Automatic Slug Generation
When a user types a product name in either English or Spanish, the system automatically generates a unique slug:

```php
public function updatedNameEn()
{
    $this->slug_en = $this->generateUniqueSlug($this->name_en);
}

public function updatedNameEs()
{
    $this->slug_es = $this->generateUniqueSlug($this->name_es);
}
```

#### 2. Unique Slug Algorithm
The `generateUniqueSlug()` method implements a smart algorithm:

```php
private function generateUniqueSlug($name)
{
    if (empty($name)) {
        return '';
    }

    $baseSlug = Str::slug($name);
    $slug = $baseSlug;
    $counter = 1;

    // Keep checking until we find a unique slug
    while ($this->slugExists($slug)) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}
```

**Algorithm Steps**:
1. Generate base slug from product name using `Str::slug()`
2. Check if the slug already exists in the database
3. If it exists, append `-1`, `-2`, `-3`, etc. until a unique slug is found
4. Return the unique slug

#### 3. Database Existence Check
The `slugExists()` method checks both English and Spanish slugs:

```php
private function slugExists($slug)
{
    $query = ProductEloquentModel::where(function($q) use ($slug) {
        $q->where('slug->en', $slug)
          ->orWhere('slug->es', $slug);
    });

    // If editing, exclude current product from the check
    if ($this->isEditing && $this->productId) {
        $query->where('id', '!=', $this->productId);
    }

    return $query->exists();
}
```

**Features**:
- Checks both `slug->en` and `slug->es` JSON fields
- When editing existing products, excludes the current product from uniqueness check
- Prevents false positives when updating the same product

## Examples

### Creating New Products
- **Product 1**: "Louis Vuitton Bag" → slug: `louis-vuitton-bag`
- **Product 2**: "Louis Vuitton Bag" → slug: `louis-vuitton-bag-1`
- **Product 3**: "Louis Vuitton Bag" → slug: `louis-vuitton-bag-2`

### Editing Existing Products
- When editing "Louis Vuitton Bag" (slug: `louis-vuitton-bag`), the system won't consider its own slug as a conflict
- Only checks for conflicts with other products

## Benefits

1. **Prevents Database Errors**: Eliminates unique constraint violations
2. **User-Friendly**: Happens automatically without user intervention
3. **Intuitive Naming**: Uses incremental numbering for duplicates
4. **Edit-Safe**: Properly handles editing existing products
5. **Bilingual Support**: Works for both English and Spanish slugs

## Database Schema

The system works with the translatable `slug` JSON column in the `products` table:

```sql
slug JSON -- Contains: {"en": "english-slug", "es": "spanish-slug"}
```

## Testing

Unit tests were created to verify the functionality:

- **Location**: `tests/Feature/ProductSlugUniquenessTest.php`, `tests/Unit/ProductFormSlugTest.php`
- **Coverage**: Tests unique slug generation, database conflicts, and editing scenarios

## Future Considerations

- The algorithm could be extended to check for more sophisticated conflicts
- Consider implementing slug history to prevent URL changes
- Could add manual slug override option for advanced users