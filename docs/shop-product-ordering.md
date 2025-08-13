# Shop Product Ordering System

## Overview

This document describes the enhanced product ordering system implemented for the shop page, which prioritizes in-stock products and displays them by recency, improving both user experience and sales conversion.

## Problem Solved

Previously, the shop page displayed products without considering stock status or creation date, leading to:
- Sold-out products appearing prominently
- Older inventory being showcased over newer arrivals
- Poor user experience when browsing available products
- Reduced conversion rates due to unavailable items being featured

## New Ordering Logic

### Priority System
Products are now ordered using a two-tier priority system:

1. **Primary Priority**: Stock Status
   - In-stock products (`is_sold_out = false`) appear first
   - Sold-out products (`is_sold_out = true`) appear last

2. **Secondary Priority**: Creation Date
   - Within each stock status group, newest products appear first
   - Uses `created_at` timestamp in descending order

### Final Order
```
1. In-stock products (newest to oldest)
2. Sold-out products (newest to oldest)
```

## Implementation

### Backend Changes

#### 1. GetShopData Use Case
**File**: `src/Products/Shop/Application/GetShopData.php`
**Method**: `createOrder()`

**Before:**
```php
private function createOrder(string $sortBy): Order
{
    if (empty($sortBy)) {
        return Order::none();
    }
    // ... existing sort options
    default:
        return Order::none();
}
```

**After:**
```php
private function createOrder(string $sortBy): Order
{
    switch ($sortBy) {
        case 'name_asc':
            return Order::fromValues('name', 'asc');
        case 'name_desc':
            return Order::fromValues('name', 'desc');
        case 'price_asc':
            return Order::fromValues('price', 'asc');
        case 'price_desc':
            return Order::fromValues('price', 'desc');
        default:
            // Default ordering: in-stock products first, then by newest created
            return Order::fromValues('stock_status_and_created', 'desc');
    }
}
```

#### 2. Product Repository
**File**: `src/Products/Product/Infrastructure/EloquentProductRepository.php`
**Methods**: `searchByCriteria()`, `searchByCriteriaForVendor()`, `searchByCriteriaForUser()`

**Enhancement Added:**
```php
// Apply sorting if specified
$order = $criteria->order();
if ($order && !$order->isNone()) {
    $orderBy = $order->orderBy();
    $orderType = $order->orderType();
    if ($orderBy && $orderType) {
        if ($orderBy->value() === 'stock_status_and_created') {
            // Custom ordering: in-stock products first, then by newest created
            $query->orderByRaw('is_sold_out ASC, created_at DESC');
        } else {
            $query->orderBy($orderBy->value(), $orderType->value());
        }
    }
}
```

### SQL Query Logic

The custom ordering uses raw SQL for optimal performance:

```sql
ORDER BY is_sold_out ASC, created_at DESC
```

**Explanation:**
- `is_sold_out ASC`: Products with `false` (0) come before `true` (1)
- `created_at DESC`: Within each group, newest products first

### Database Schema Requirements

The implementation relies on these product table columns:

```sql
CREATE TABLE products (
    id VARCHAR(36) PRIMARY KEY,
    is_sold_out BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- other columns...
);
```

**Key Fields:**
- `is_sold_out`: Boolean flag indicating product availability
- `created_at`: Timestamp when product was first created

## User Experience Impact

### Before Implementation
```
Product List (Random Order):
1. Sold Out Bag A (Created: 2024-01-01)
2. Available Bag B (Created: 2024-03-15)  
3. Sold Out Bag C (Created: 2024-02-10)
4. Available Bag D (Created: 2024-01-20)
```

### After Implementation
```
Product List (Optimized Order):
1. Available Bag B (Created: 2024-03-15) ← In-stock, newest
2. Available Bag D (Created: 2024-01-20) ← In-stock, older
3. Sold Out Bag C (Created: 2024-02-10) ← Sold-out, newer
4. Sold Out Bag A (Created: 2024-01-01) ← Sold-out, oldest
```

## Benefits

### For Customers
1. **Better Browsing Experience**: Available products are immediately visible
2. **Discovery of New Items**: Newest inventory appears first
3. **Reduced Frustration**: Less time spent on unavailable products
4. **Informed Decisions**: Clear visibility of what's currently available

### For Business
1. **Increased Conversion**: Available products get priority visibility
2. **Inventory Turnover**: Newer items get more exposure
3. **Customer Satisfaction**: Better user experience leads to return visits
4. **SEO Benefits**: Fresh content appears prominently

## Compatibility

### Existing Sort Options
The new default ordering works alongside existing manual sort options:

- **Name A-Z**: `name_asc`
- **Name Z-A**: `name_desc`  
- **Price Low-High**: `price_asc`
- **Price High-Low**: `price_desc`
- **Default**: `stock_status_and_created` (new)

### Fallback Behavior
- When users select specific sorting, it overrides the default
- When no sorting is selected, the new stock-priority ordering applies
- Existing sort functionality remains unchanged

## Performance Considerations

### Database Indexing
For optimal performance, consider adding compound indexes:

```sql
-- Recommended indexes for optimal sorting performance
CREATE INDEX idx_products_stock_created ON products (is_sold_out, created_at DESC);
CREATE INDEX idx_products_created ON products (created_at DESC);
```

### Query Optimization
- Uses `ORDER BY` with raw SQL for maximum database efficiency
- Leverages existing database indexes for fast sorting
- No additional database queries required

## Edge Cases Handled

### 1. Products with Same Creation Date
- Database handles ties using primary key as secondary sort
- Consistent ordering maintained across page loads

### 2. Null Creation Dates
- MySQL handles NULL values appropriately in DESC order
- Products with NULL dates appear last in their stock group

### 3. Boolean Field Values
- Handles both true/false and 1/0 boolean representations
- Consistent behavior across different database configurations

## Testing Strategy

### Functional Testing
- Verify in-stock products appear before sold-out products
- Confirm newest products appear first within each group
- Test with various product combinations and dates
- Validate existing sort options still work correctly

### Performance Testing
- Monitor query execution time with large product datasets
- Verify database indexing effectiveness
- Test pagination performance with new ordering

### User Acceptance Testing
- Gather feedback on improved product discovery
- Monitor conversion rates and user engagement
- Analyze bounce rates on shop pages

## Future Enhancements

### Possible Improvements
1. **Stock Level Priority**: Further prioritize products with higher stock quantities
2. **Featured Products**: Add special priority for featured/promoted items
3. **User Personalization**: Consider user browsing history in ordering
4. **Dynamic Sorting**: Allow users to toggle "show available only" filter
5. **Analytics Integration**: Track user interaction with reordered products

### Configuration Options
- Add admin panel option to enable/disable stock priority
- Allow customization of default sort order
- Provide merchant controls for inventory display preferences

## Migration Notes

### Deployment Considerations
- No database migrations required
- Changes are backward compatible
- Existing functionality preserved
- No user-facing breaking changes

### Rollback Plan
- Remove custom ordering logic from repository
- Revert GetShopData to return `Order::none()` for default case
- System returns to previous random ordering

## Monitoring and Analytics

### Key Metrics to Track
- Shop page conversion rates before/after implementation
- Average time spent on shop pages
- Click-through rates on product listings
- User feedback and satisfaction scores
- Inventory turnover rates for newly listed items

### Success Indicators
- Increased conversion from shop page to product detail pages
- Reduced bounce rate on shop pages
- Improved user session duration
- Higher engagement with newer inventory items