# Search Functionality Documentation

## Overview
The Bags & Tea marketplace features a comprehensive real-time search system with typo tolerance, multi-field searching, and responsive design for both desktop and mobile users.

## Architecture

### Components Structure
```
src/Shared/Frontend/
├── SearchBar.php           # Desktop search component
└── SearchBarMobile.php     # Mobile search component

src/Products/Product/Application/
└── ProductSearcher.php     # Search business logic

resources/views/livewire/
├── search-bar.blade.php          # Desktop template
└── search-bar-mobile.blade.php   # Mobile template

resources/lang/{locale}/components/
└── search-bar.php          # Translations (en/es)
```

### Key Features

#### 1. Real-time Search
- **Debounced input**: 300ms delay to prevent excessive API calls
- **Live suggestions**: Instant dropdown with matching product names
- **Product results**: Detailed results with images, prices, and direct links
- **Loading indicators**: Visual feedback during search operations

#### 2. Typo Tolerance
- **SOUNDEX algorithm**: Fuzzy matching for common misspellings
- **Multi-field coverage**: Applies to product names, brands, categories
- **Case-insensitive**: All searches work regardless of case

#### 3. Multi-field Search
Searches across multiple product attributes:
- Product names (translatable)
- SKU codes
- Product descriptions (translatable)
- Brand names (translatable)
- Category names (translatable)
- Attribute names (translatable)

#### 4. Smart Relevance Ranking
Results ordered by relevance:
1. Exact matches
2. Starts with query
3. Contains query
4. SKU matches
5. SOUNDEX fuzzy matches

## Technical Implementation

### Backend Logic (ProductSearcher.php)

#### Search Method
```php
public function search(string $query, string $locale = 'en', int $limit = 10): array
```

**Features:**
- Sanitizes and validates input
- Performs fuzzy search with typo tolerance
- Formats results with proper image URLs
- Builds locale-specific product URLs

#### Key Query Techniques
- **JSON field queries**: `JSON_UNQUOTE(JSON_EXTRACT(name, '$.locale'))`
- **SOUNDEX fuzzy matching**: `SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(name, '$.locale'))) = SOUNDEX(?)`
- **Case-insensitive search**: `LOWER()` functions for all text comparisons
- **Relationship searches**: `whereHas()` for brands, categories, attributes

#### Image URL Handling
```php
// Check if it's R2 URL or local storage
if (str_starts_with($filePath, 'https://') || str_contains($filePath, 'r2.cloudflarestorage.com')) {
    $imageUrl = $filePath; // Use R2 URL directly
} else {
    $imageUrl = asset($filePath); // Use asset() for local storage
}
```

### Frontend Components (Livewire)

#### SearchBar Properties
```php
public string $query = '';           # User input
public array $results = [];          # Search results
public array $suggestions = [];      # Quick suggestions
public bool $showResults = false;    # Dropdown visibility
public bool $isLoading = false;      # Loading state
```

#### Key Methods
- `updatedQuery()`: Triggered on input change with debounce
- `performSearch()`: Executes search via ProductSearcher
- `selectSuggestion()`: Applies suggestion to search
- `hideResults()`: Closes dropdown
- `resetSearch()`: Clears all search state

### UI/UX Design

#### Visual Integration
- **Seamless dropdown**: Input and results appear as one unified element
- **Dynamic border radius**: `rounded-t-full` input connects to `rounded-b-[2.5rem]` dropdown
- **Consistent styling**: Matching border colors (`border-gray-300`)
- **No focus rings**: Clean, minimal appearance without blue outlines

#### Responsive Behavior
- **Desktop**: Full-width search in header with larger dropdown
- **Mobile**: Compact search in hamburger menu with optimized spacing
- **Touch-friendly**: Properly sized touch targets for mobile interaction

#### State Management
- **Empty state**: "No results found" message for invalid queries
- **Loading state**: Spinner animation during search operations
- **Results state**: Organized sections for suggestions and products
- **Error state**: Graceful handling of search failures

## Translations

### Translation Files
- `resources/lang/en/components/search-bar.php`
- `resources/lang/es/components/search-bar.php`

### Translation Keys
```php
'suggestions' => 'Suggestions|Sugerencias',
'products' => 'Products|Productos', 
'no-results' => 'No results found|No se encontraron resultados'
```

## Integration Points

### Header Integration
```php
// Desktop header
<div class="bg-background-color-4 w-full flex items-center py-4 pl-16">
    @livewire('shared.search-bar')
</div>

// Mobile header
<div id="hamburgerMenuOptions" class="hidden bg-background-color-4 w-full pt-4 mt-4">
    @livewire('shared.search-bar-mobile')
</div>
```

### Livewire Registration
```php
// AppServiceProvider.php
Livewire::component('shared.search-bar', \Src\Shared\Frontend\SearchBar::class);
Livewire::component('shared.search-bar-mobile', \Src\Shared\Frontend\SearchBarMobile::class);
```

## Performance Considerations

### Database Optimization
- **Proper indexing**: Ensure indexes on searchable fields
- **Query limiting**: Default limit of 8 products, 5 suggestions
- **Eager loading**: Pre-load related models (brand, categories, images)
- **Filtered results**: Exclude hidden products from search

### Frontend Optimization
- **Debounced input**: Prevents excessive server requests
- **Efficient re-rendering**: Livewire optimizations for real-time updates
- **Minimal DOM updates**: Alpine.js for client-side interactions
- **Image optimization**: Proper asset handling for different storage types

## Testing & Quality Assurance

### Test Cases
1. **Basic functionality**: Search returns relevant results
2. **Typo tolerance**: Misspelled queries return correct results  
3. **Multi-language**: Search works in both English and Spanish
4. **Edge cases**: Empty queries, special characters, very long strings
5. **Performance**: Response times under 300ms for typical queries
6. **UI/UX**: Dropdown behavior, keyboard navigation, mobile responsiveness

### Common Issues & Solutions

#### No Results Appearing
- **Cause**: Dropdown condition excludes empty results
- **Solution**: Change condition from `$showResults && (results || suggestions)` to `$showResults`

#### Broken Images in Results
- **Cause**: Incorrect image URL handling
- **Solution**: Check for R2 URLs vs local paths, use `asset()` appropriately

#### Search Not Working for Accented Characters
- **Cause**: Database collation issues
- **Solution**: Ensure UTF8MB4 collation and proper character encoding

#### JSON Query Errors
- **Cause**: Incorrect MySQL JSON syntax
- **Solution**: Use `JSON_UNQUOTE(JSON_EXTRACT(field, '$.locale'))` syntax

## Future Enhancements

### Planned Features
- **Search analytics**: Track popular search terms and no-result queries
- **Advanced filters**: Price ranges, availability, brand filters within search
- **Search history**: Remember recent searches per user session
- **Voice search**: Integration with browser speech recognition
- **Search shortcuts**: Keyboard shortcuts for power users

### Technical Improvements
- **Elasticsearch integration**: For more advanced search capabilities
- **Search result caching**: Redis cache for popular search terms
- **Autocomplete API**: Dedicated endpoint for typeahead suggestions
- **Search result ranking**: Machine learning for personalized results
- **Full-text search**: MySQL FULLTEXT indexes for better text matching