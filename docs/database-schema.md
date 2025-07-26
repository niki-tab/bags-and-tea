# Database Schema Documentation

## Overview
The Bags & Tea marketplace platform uses a comprehensive database schema designed with **UUID primary keys** for scalability and security. The schema supports multi-language content, vendor management, complex product variations, and flexible content management.

## Schema Architecture Principles

### 1. Primary Key Strategy
- **UUID primary keys** for all business entities (products, categories, vendors, etc.)
- **Auto-increment IDs** for Laravel system tables (failed_jobs, personal_access_tokens)
- **Composite keys** for pivot tables (user_roles, product relationships)

### 2. Multi-language Support
- **JSON columns** for translatable fields (name, slug, descriptions, meta fields)
- **Spatie Translatable package** integration for seamless language switching
- **English/Spanish** language support with fallback mechanisms

### 3. Marketplace Architecture
- **Single vendor currently** (Bags & Tea) with multi-vendor ready infrastructure
- **Vendor management** with commission rates, billing, and status tracking
- **Product ownership** through vendor relationships

## Core Tables

### User Management System

#### users
```sql
id (uuid, primary)
name (string)
email (string, unique)
email_verified_at (timestamp, nullable)
password (string)
remember_token (string, nullable)
created_at (timestamp)
updated_at (timestamp)
```
**Relationships:**
- `hasMany` vendors through vendor.user_id
- `belongsToMany` roles through user_roles pivot table

**Schema Evolution:** Originally contained direct role columns (buyer_id, vendor_id, admin_id) which were refactored into a many-to-many relationship system.

#### roles
```sql
id (uuid, primary)
name (string)
display_name (string)
created_at (timestamp)
updated_at (timestamp)
```
**Purpose:** Define system roles (admin, vendor, buyer, etc.)

#### user_roles (Pivot Table)
```sql
user_id (uuid, foreign key ’ users.id CASCADE)
role_id (uuid, foreign key ’ roles.id CASCADE)
created_at (timestamp)
updated_at (timestamp)

PRIMARY KEY: [user_id, role_id]
INDEXES: user_id, role_id
```

### Vendor/Marketplace System

#### vendors
```sql
id (uuid, primary)
user_id (uuid, unique, foreign key ’ users.id SET NULL)
business_name (string)
tax_id (string, nullable)
billing_address (json, nullable)
shipping_address (json, nullable)
phone (string, nullable)
website (string, nullable)
description (text, nullable)
logo (string, nullable)
status (enum: active, inactive, pending, default: active)
commission_rate (decimal 5,2, default: 0.00)
created_at (timestamp)
updated_at (timestamp)

INDEXES: user_id, status
```
**Purpose:** Marketplace vendor management with billing and commission tracking

### Product Catalog System

#### products
```sql
id (uuid, primary)
vendor_id (uuid, foreign key ’ vendors.id SET NULL)
brand_id (uuid, foreign key ’ brands.id SET NULL)
quality_id (uuid, foreign key ’ qualities.id CASCADE)
name (json) -- Translatable field
slug (json) -- Translatable field
sku (string, nullable)
status (string, default: "approved")
description_1 (json, nullable) -- Translatable field
description_2 (json, nullable) -- Translatable field
origin_country (string, nullable)
product_type (enum: simple, variable, default: simple)
price (float)
discounted_price (float, nullable)
deal_price (float, nullable)
sell_mode (enum: unit, grouped, default: unit)
sell_mode_quantity (integer, default: 1)
stock (integer, default: 1)
stock_unit (enum: unit, meter, roll, default: unit)
out_of_stock (boolean, default: false)
is_sold_out (boolean, default: false)
is_hidden (boolean, default: false)
featured (boolean, default: false)
featured_position (integer, nullable)
height (float, nullable)
width (float, nullable)
depth (float, nullable)
weight (float, nullable)
meta_title (json, nullable) -- Translatable field
meta_description (json, nullable) -- Translatable field
created_at (timestamp)
updated_at (timestamp)

INDEXES: vendor_id, brand_id, quality_id, featured, is_hidden, is_sold_out
```

**Schema Evolution:**
- Originally had string 'brand' field ’ evolved to brands table relationship
- Originally had single 'image' field ’ evolved to product_media table
- Added physical dimensions (height, width, depth, weight) later
- Added SKU field for inventory management

#### product_media
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
file_path (string)
file_name (string)
file_type (enum: image, video)
mime_type (string, nullable)
file_size (integer, nullable)
sort_order (integer, default: 0)
is_primary (boolean, default: false)
alt_text (string, nullable)
created_at (timestamp)
updated_at (timestamp)

INDEXES: [product_id, sort_order], is_primary
```
**Purpose:** Multi-media support for products with R2 CloudFlare Storage integration

### Product Variations System

#### product_size_variations
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
size_name (string)
size_description (text, nullable)
order (integer, default: 0)
created_at (timestamp)
updated_at (timestamp)
```

#### product_quantity_variations
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
quantity_name (string)
quantity_description (text, nullable)
order (integer, default: 0)
created_at (timestamp)
updated_at (timestamp)
```

#### product_size_variation_quantity_variation_prices
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
product_size_variation_id (uuid, foreign key ’ product_size_variations.id CASCADE)
product_quantity_variation_id (uuid, foreign key ’ product_quantity_variations.id CASCADE)
shop_price (decimal 8,2)
shop_discounted_price (decimal 8,2, nullable)
sale_price (decimal 8,2, nullable)
discounted_price (decimal 8,2, nullable)
currency (string, default: "¬")
created_at (timestamp)
updated_at (timestamp)
```
**Purpose:** Complex pricing matrix for size/quantity combinations

### Taxonomy System

#### categories
```sql
id (uuid, primary)
name (json) -- Translatable field
slug (json) -- Translatable field
description_1 (json, nullable) -- Translatable field
description_2 (json, nullable) -- Translatable field
parent_id (uuid, nullable, foreign key ’ categories.id CASCADE)
display_order (integer, default: 0)
is_active (boolean, default: true)
meta_title (json, nullable) -- Translatable field
meta_description (json, nullable) -- Translatable field
created_at (timestamp)
updated_at (timestamp)

INDEXES: [parent_id, is_active], [is_active, display_order]
```
**Features:** Hierarchical categories with unlimited nesting depth

#### attributes
```sql
id (uuid, primary)
name (json) -- Translatable field
slug (json) -- Translatable field
description_1 (json, nullable) -- Translatable field
description_2 (json, nullable) -- Translatable field
parent_id (uuid, nullable, foreign key ’ attributes.id CASCADE)
display_order (integer, default: 0)
is_active (boolean, default: true)
meta_title (json, nullable) -- Translatable field
meta_description (json, nullable) -- Translatable field
created_at (timestamp)
updated_at (timestamp)

INDEXES: [parent_id, is_active], [is_active, display_order]
```
**Features:** Hierarchical product attributes (color, material, size, etc.)

#### brands
```sql
id (uuid, primary)
name (json) -- Translatable field
slug (json) -- Translatable field
description_1 (json, nullable) -- Translatable field
description_2 (json, nullable) -- Translatable field
logo_url (string, nullable)
display_order (integer, default: 0)
is_active (boolean, default: true)
created_at (timestamp)
updated_at (timestamp)

INDEXES: [is_active, display_order]
```
**Features:** Brand management with logo support

#### qualities
```sql
id (uuid, primary)
name (string)
code (string, nullable)
display_order (integer, default: 0)
created_at (timestamp)
updated_at (timestamp)
```
**Purpose:** Product quality classifications (New, Like New, Good, Fair, etc.)

### Product Relationship Tables

#### product_category (Many-to-Many)
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
category_id (uuid, foreign key ’ categories.id CASCADE)
created_at (timestamp)
updated_at (timestamp)

UNIQUE: [product_id, category_id]
INDEXES: product_id, category_id
```

#### product_attribute (Many-to-Many)
```sql
id (uuid, primary)
product_id (uuid, foreign key ’ products.id CASCADE)
attribute_id (uuid, foreign key ’ attributes.id CASCADE)
created_at (timestamp)
updated_at (timestamp)

UNIQUE: [product_id, attribute_id]
INDEXES: product_id, attribute_id
```

### Shop Filtering System

#### shop_filters
```sql
id (uuid, primary)
name (json) -- Translatable field
type (enum: category, attribute, brand, quality, price)
reference_table (string, nullable)
product_column (string, nullable)
config (json, nullable)
display_order (integer, default: 0)
is_active (boolean, default: true)
created_at (timestamp)
updated_at (timestamp)

INDEXES: [is_active, display_order]
```
**Purpose:** Dynamic shop filtering configuration for frontend

### Content Management System

#### blog_articles
```sql
id (uuid, primary)
title (string)
slug (string, unique)
body (longtext)
main_image (string, nullable)
meta_title (string, nullable)
meta_description (longtext, nullable)
meta_keywords (text, nullable)
state (string, nullable)
is_visible (boolean, default: true)
created_at (timestamp)
updated_at (timestamp)

INDEXES: slug, is_visible, state
```

### CRM System

#### crm_forms
```sql
id (uuid, primary)
form_identifier (string)
form_name (string)
form_description (text, nullable)
form_fields (json)
is_active (boolean, default: true)
created_at (timestamp)
updated_at (timestamp)
```

#### crm_form_submissions
```sql
id (uuid, primary)
crm_form_id (uuid, foreign key ’ crm_forms.id CASCADE)
crm_form_answers (json)
created_at (timestamp)
updated_at (timestamp)

INDEX: crm_form_id
```

### Laravel System Tables

#### password_reset_tokens
```sql
email (string, primary)
token (string)
created_at (timestamp, nullable)
```

#### failed_jobs
```sql
id (bigint auto_increment, primary)
uuid (string, unique)
connection (text)
queue (text)
payload (longtext)
exception (longtext)
failed_at (timestamp, default: CURRENT_TIMESTAMP)
```

#### personal_access_tokens
```sql
id (bigint auto_increment, primary)
tokenable_type (string)
tokenable_id (bigint)
name (string)
token (string, unique)
abilities (text, nullable)
last_used_at (timestamp, nullable)
expires_at (timestamp, nullable)
created_at (timestamp)
updated_at (timestamp)

INDEXES: [tokenable_type, tokenable_id], token
```

## Key Relationships

### Product-Centric Relationships
```
products
   belongsTo vendor
   belongsTo brand  
   belongsTo quality
   belongsToMany categories (through product_category)
   belongsToMany attributes (through product_attribute)
   hasMany media (product_media)
   hasMany sizeVariations (product_size_variations)
   hasMany quantityVariations (product_quantity_variations)
   hasMany variationPrices (product_size_variation_quantity_variation_prices)
```

### User-Vendor Relationships
```
users
   hasOne vendor
   belongsToMany roles (through user_roles)

vendors
   belongsTo user
   hasMany products
```

### Taxonomy Hierarchies
```
categories
   belongsTo parent (self-referencing)
   hasMany children (self-referencing)
   belongsToMany products (through product_category)

attributes  
   belongsTo parent (self-referencing)
   hasMany children (self-referencing)
   belongsToMany products (through product_attribute)
```

## Data Types and Constraints

### JSON Fields (Translatable Content)
All translatable fields use JSON format:
```json
{
  "en": "English content",
  "es": "Contenido en español"
}
```

### Enum Fields
- **product_type**: ['simple', 'variable']
- **sell_mode**: ['unit', 'grouped'] 
- **stock_unit**: ['unit', 'meter', 'roll']
- **vendor_status**: ['active', 'inactive', 'pending']
- **filter_type**: ['category', 'attribute', 'brand', 'quality', 'price']

### Decimal Precision
- **Prices**: decimal(8,2) for currency values
- **Commission rates**: decimal(5,2) for percentage values

## Search Optimization

### Searchable Fields
Products can be searched by:
- **name** (JSON translatable)
- **sku** (string)
- **description_1, description_2** (JSON translatable)
- **brand.name** (JSON translatable, via relationship)
- **categories.name** (JSON translatable, via relationship)
- **attributes.name** (JSON translatable, via relationship)

### Search Query Pattern
```sql
-- Example search query for "bag" in English
SELECT * FROM products 
WHERE JSON_UNQUOTE(JSON_EXTRACT(name, '$.en')) LIKE '%bag%'
   OR sku LIKE '%bag%'
   OR SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) = SOUNDEX('bag')
```

## Performance Considerations

### Strategic Indexes
- **Product filtering**: vendor_id, brand_id, quality_id, is_hidden, is_sold_out
- **Category hierarchy**: [parent_id, is_active], [is_active, display_order]
- **Product relationships**: Composite indexes on pivot tables
- **Media ordering**: [product_id, sort_order]

### Recommended Additional Indexes
For optimal search performance, consider adding:
```sql
-- Full-text search indexes
ALTER TABLE products ADD FULLTEXT(sku);

-- JSON path indexes (MySQL 8.0+)
ALTER TABLE products ADD INDEX idx_product_name_en ((JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))));
ALTER TABLE products ADD INDEX idx_product_name_es ((JSON_UNQUOTE(JSON_EXTRACT(name, '$.es'))));
```

## Schema Evolution History

### Major Changes
1. **2025-05-27**: Refactored user roles from direct columns to many-to-many relationship
2. **2025-06-03**: Migrated from string brand column to brands table relationship  
3. **2025-06-15**: Implemented translatable JSON fields for categories, attributes, brands
4. **2025-07-04**: Added vendor system for marketplace functionality
5. **2025-07-05**: Replaced single image column with product_media table
6. **2025-07-14**: Added physical dimensions to products
7. **2025-07-16**: Added SKU field for inventory management

### Translation Evolution
- **Phase 1**: Simple string fields
- **Phase 2**: Separate translation tables (legacy approach)
- **Phase 3**: JSON-based translatable fields (current approach)

This schema represents a mature e-commerce marketplace with comprehensive multi-language support, flexible product variations, and scalable vendor management capabilities.