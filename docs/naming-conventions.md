# Naming Conventions

## Use Case Naming

### Reserved Words
- **"Get" and "Post"** are reserved for infrastructure layer files only
- These words should NEVER be used in use case naming

### Use Case Naming Rules
- Use descriptive verbs that clearly indicate the action
- Preferred verbs: "Retrieve", "Add", "Update", "Remove", "Create", "Delete", "Search"
- Follow the pattern: `{Verb}{Entity}{OptionalContext}`

### Examples

####  Correct Use Case Names
```php
// Application Layer (Use Cases)
RetrieveCartContents.php
AddItemToCart.php
UpdateCartItemQuantity.php
RemoveItemFromCart.php
ClearCart.php
MergeGuestCartWithUser.php
SearchProducts.php
CreateProduct.php
UpdateProductDetails.php
```

#### L Incorrect Use Case Names  
```php
// These are WRONG for use cases
GetCartContents.php     // "Get" is reserved for infrastructure
PostCartItem.php        // "Post" is reserved for infrastructure
GetProductById.php      // "Get" is reserved for infrastructure
```

####  Correct Infrastructure Names
```php
// Infrastructure Layer (Allowed to use reserved words)
GetCartDataFromDatabase.php
PostCartItemToApi.php
GetProductByIdRepository.php
```

## Use Case Structure

### Required Methods Only
Use cases should contain ONLY these methods:
- `__construct()` - For dependency injection
- `__invoke()` - The single entry point for the use case

### L Avoid Additional Methods
```php
// DON'T do this in use cases
class AddItemToCart 
{
    public function __construct() {}
    public function __invoke() {}
    
    // L Don't add these
    private function validateItem() {}
    private function checkDuplicates() {}
    private function updateQuantity() {}
}
```

###  Keep Use Cases Simple
```php
//  Do this instead
class AddItemToCart 
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}
    
    public function __invoke(
        string $productId,
        int $quantity,
        ?string $userId = null,
        ?string $sessionId = null
    ): array {
        // All logic in single method
        // Keep it focused and simple
    }
}
```

## Domain Modeling

### Avoid Rich Domain Entities
- Keep domain entities simple
- Don't create complex value objects unless absolutely necessary
- Use repository interfaces for data access patterns
- Keep business logic in use cases, not entities

### Example Structure
```php
//  Simple approach (preferred)
Domain/
   CartRepository.php (interface only)

// L Over-engineered approach (avoid)
Domain/
   Cart.php (rich entity)
   CartItem.php (value object)
   CartId.php (value object)
   ProductId.php (value object)
   CartRepository.php (interface)
```

## File and Directory Naming

### Module Structure
Follow this pattern for each domain:
```
src/{DomainName}/
   Application/     # Use cases (avoid "Get", "Post")
   Domain/          # Repository interfaces and core contracts
   Infrastructure/  # Implementations (can use "Get", "Post")
   Frontend/        # Livewire components
```

### Component Registration
```php
// In AppServiceProvider
Livewire::component('domain.action', \Src\Domain\Frontend\ComponentName::class);

// Examples
Livewire::component('cart.page', \Src\Cart\Frontend\CartPage::class);
Livewire::component('cart.icon', \Src\Cart\Frontend\CartIcon::class);
```

## Translation File Naming

### Component Translations
```
resources/lang/
   en/components/{feature}.php
   es/components/{feature}.php

// Examples
resources/lang/en/components/cart.php
resources/lang/es/components/cart.php
```

### Translation Key Naming
- Use kebab-case for translation keys
- Group related translations logically
- Provide both languages

```php
// cart.php
return [
    'title' => 'Shopping Cart',
    'add-to-cart' => 'Add to Cart',
    'item-added' => 'Item added successfully',
    'error-adding' => 'Error adding item',
    'empty-description' => 'Add some products to get started',
];
```

## Database Naming

### Table Naming
- Use snake_case for table names
- Use plural nouns for table names
- Foreign keys: `{table}_id`

### Examples
```sql
carts
cart_items
products
product_media
categories
attributes
```

### Model Naming
- Eloquent models use singular PascalCase
- End with "EloquentModel" for clarity

```php
// Examples
CartEloquentModel.php
CartItemEloquentModel.php
ProductEloquentModel.php
```

## Route Naming

### Route Patterns
```php
// Pattern: {feature}.{action}.{locale}
Route::get('/cart', ...)->name('cart.show.en');
Route::get('/carrito', ...)->name('cart.show.es');

// Multi-locale routes
Route::get('/blog', ...)->name('blog.show.en-es');
```

## CSS Class Naming

### Tailwind Class Organization
- Group by: layout, spacing, colors, typography
- Use project color variables: `bg-background-color-4`, `text-color-2`
- Responsive prefixes: `lg:`, `md:`, `sm:`

### Brand Colors
```php
// Consistent brand colors
'bg-[#CA2530]'      // Primary red
'hover:bg-[#A01E28]' // Darker red
'bg-background-color-4' // Project background
'text-color-2'      // Project text color
```

This naming convention ensures consistency across the entire codebase and makes it easier for developers to understand and maintain the project structure.