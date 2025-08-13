# Bags & Tea - Marketplace Platform Context

## Overview
This is a Laravel application using **Hexagonal Architecture** (Ports and Adapters) with Domain-Driven Design principles. The project is a **marketplace platform** for luxury bags, currently operating with a single vendor (Bags & Tea) but designed to support multiple vendors in the future.

## Core Architecture Principles

### 1. Hexagonal Architecture Layers
Each domain module contains four layers:
- **Frontend**: Livewire components and presentation logic
- **Application**: Use cases and application services
- **Domain**: Business logic, entities, and repository interfaces
- **Infrastructure**: External implementations (database, APIs, etc.)

### 2. Module Structure
```
src/
├── Products/                 # Products domain
│   ├── Product/             # Product entity module
│   │   ├── Application/     # ProductSearcher, ProductCreator, ProductByCriteriaSearcher
│   │   ├── Domain/          # ProductRepository interface
│   │   ├── Infrastructure/  # EloquentProductRepository, ProductEloquentModel
│   │   └── Frontend/        # ProductDetail, ProductAdditionalInformation
│   ├── Brands/              # Brands entity module
│   ├── Quality/             # Quality entity module
│   └── Shop/                # Shop frontend component
├── Cart/                    # Shopping cart domain
│   ├── Application/         # AddItemToCart, RemoveItemFromCart, UpdateCartItemQuantity, etc.
│   ├── Domain/              # CartRepository interface
│   ├── Infrastructure/      # EloquentCartRepository, CartEloquentModel, CartItemEloquentModel
│   └── Frontend/           # CartPage, CartIcon, AddToCartButton
├── Categories/              # Categories domain
│   ├── Infrastructure/      # CategoryEloquentModel
│   └── Eloquent/           # Category models
├── Attributes/              # Product attributes domain
│   ├── Infrastructure/      # AttributeEloquentModel
│   └── Eloquent/           # Attribute models
├── Vendors/                 # Vendors domain (marketplace vendors)
│   └── Infrastructure/      # VendorEloquentModel
├── Blog/                    # Blog/Content domain
│   ├── Articles/           # Articles entity module
│   │   ├── Application/    # Article use cases
│   │   ├── Domain/         # ArticleRepository interface
│   │   ├── Infrastructure/ # EloquentArticleRepository
│   │   └── Frontend/       # ShowArticle, ShowAllArticle, AddEditArticle
├── Admin/                   # Admin management domain
│   ├── Product/            # Admin product management
│   │   └── Frontend/       # ShowAllProduct
│   ├── Blog/               # Admin blog management
│   └── Crm/                # Admin CRM management
│       └── Frontend/       # ShowAllFormSubmissions, ShowSubmissionDetail
├── Crm/                     # CRM functionality
│   └── Forms/              # Contact forms
│       ├── Application/    # RetrieveFormsList, RetrieveFormSubmissions, RetrieveSubmissionDetail
│       ├── Domain/         # FormRepository interface
│       ├── Infrastructure/ # EloquentFormRepository, FormEloquentModel, FormSubmissionEloquentModel
│       └── Frontend/       # Form (contact form component)
└── Shared/                  # Cross-cutting concerns
    └── Frontend/           # LanguageSelector, CookieBanner, Pagination, WhatsappWidget, SearchBar
```

## Technical Stack
- **Framework**: Laravel (latest)
- **Frontend**: Livewire 3.x + Alpine.js (included with Livewire)
- **PHP**: 8.x
- **Database**: MySQL with UUID and auto-increment primary keys
- **CSS**: Tailwind CSS
- **Build Tool**: Vite
- **Container**: Docker (prefixed with `docker compose exec app`)
- **Languages**: Multi-language support (English/Spanish)
- **Image Storage**: R2 CloudFlare Storage + local asset storage

## Key Implementation Details

### 1. Use Cases & Application Layer
- Use cases use `__invoke` method as single entry point when applicable
- **IMPORTANT**: Use cases should contain only `__construct()` and `__invoke()` methods
- **Reserved Words**: "Get" and "Post" are reserved for infrastructure layer naming only
- Use descriptive verbs in use cases: "Retrieve", "Add", "Update", "Remove" (NOT "Get")
- Application services handle complex business logic
- Example: `ProductSearcher` handles search functionality with typo tolerance
- Example: `AddItemToCart` handles cart item validation and duplicate prevention
- Use cases are injected into Livewire components

### 2. Repository Pattern
- Interfaces in Domain layer define contracts
- Infrastructure layer implements with Eloquent
- Repository methods return arrays or Eloquent models as needed
- Example: `EloquentProductRepository` implements `ProductRepository`

### 3. Livewire Components
- Located in `{Domain}/Frontend/` directories or `src/Shared/Frontend/`
- Registered in `AppServiceProvider` with proper naming
- Example: `Livewire::component('shared.search-bar', \Src\Shared\Frontend\SearchBar::class)`
- Use Alpine.js for client-side interactivity

### 4. Database Schema
- Products table with translatable fields (JSON columns)
- UUID primary keys for some entities, auto-increment for others
- Proper relationships: products → vendors, brands, categories, attributes
- Cart tables: carts (UUID PK), cart_items (UUID PK) with proper foreign keys
- Session-based carts with expires_at for guest users
- Media table for product images (R2 CloudFlare + local storage)

### 5. Search Functionality
- **ProductSearcher** application service in `src/Products/Product/Application/`
- **Multi-field search**: name, SKU, descriptions, brands, categories, attributes
- **Typo tolerance**: SOUNDEX fuzzy matching for common misspellings
- **Real-time search**: Livewire components with 300ms debounce
- **JSON query syntax**: `JSON_UNQUOTE(JSON_EXTRACT(field, '$.locale'))` for translatable fields
- **Image handling**: Checks for R2 URLs vs local paths, uses `asset()` helper appropriately

## Development Environment
- Docker commands prefixed with: `docker compose exec app`
- Example: `docker compose exec app php artisan migrate`
- Testing: `make test` or `docker compose exec app php artisan test`
- Fresh migrations: `docker compose exec app php artisan migrate:fresh --seed`

## Current Features
- **Marketplace Platform**: Single vendor (Bags & Tea) with multi-vendor architecture
- **Product Management**: Full CRUD with categories, brands, attributes, and quality ratings
- **Shopping Cart**: Full cart functionality with guest/user sessions, duplicate prevention
- **Search Functionality**: Real-time search with typo tolerance and suggestions
- **Multi-language Support**: English/Spanish with translatable content
- **Shop Frontend**: Product catalog with filtering, sorting, and optimized stock-priority ordering
- **Admin Panel**: Product, category, attribute, blog, and CRM form submissions management
- **Blog System**: Article management with multilingual support
- **CRM System**: Contact form management with submission tracking and admin panel
- **Responsive Design**: Desktop and mobile optimized components
- **Product Slug Generation**: Automatic unique slug generation for products in admin panel
- **Breadcrumb Navigation**: Smart breadcrumb system for product detail pages
- **Enhanced UX**: Functional buttons and improved navigation throughout the site

## Documentation
Recent feature implementations are documented in the `docs/` folder:
- **product-slug-generation.md**: Unique slug generation system for products
- **breadcrumb-navigation.md**: Smart breadcrumb navigation for product pages
- **button-redirects-about-us.md**: Functional button implementations on About Us page
- **shop-product-ordering.md**: Stock-priority product ordering system
- **search-functionality.md**: Comprehensive search system documentation
- **database-schema.md**: Complete database structure and relationships

## Important Notes
- **CLAUDE.md file**: Contains project-specific instructions and Docker commands
- **Translation files**: Located in `resources/lang/{locale}/components/`
- **Livewire registration**: All components registered in `AppServiceProvider`
- **JSON fields**: Use proper MySQL JSON syntax for translatable content
- **Image handling**: Check for R2 URLs vs local paths in view logic
- **Never create files unless absolutely necessary**: Always prefer editing existing files
- **No proactive documentation**: Only create docs when explicitly requested