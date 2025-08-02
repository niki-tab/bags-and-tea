# API Documentation - Bags & Tea Marketplace

## Overview
This document outlines the available routes and endpoints for the Bags & Tea marketplace platform. The application follows RESTful conventions where applicable and uses Laravel's route structure.

## Authentication
The application uses Laravel's built-in session-based authentication with role-based access control.

### Admin Authentication
- **Login Route**: `GET /admin/login`
- **Authentication**: `POST /admin/authenticate`
- **Logout**: `POST /admin/logout`
- **Middleware**: `admin` (custom middleware for admin panel access)

## Admin Panel Routes

### Dashboard Routes
All admin routes are protected by the `admin` middleware and require authentication.

#### Main Dashboard
- **Route**: `GET /admin`
- **Name**: `admin.home`
- **Controller**: `AdminPanelController@home`
- **Description**: Admin dashboard homepage
- **Access**: Admin users only

#### Product Management
- **Route**: `GET /admin/products`
- **Name**: `admin.products`
- **Controller**: `AdminPanelController@products`
- **Description**: Product management interface
- **Access**: Admin and Vendor users

#### Order Management
- **Route**: `GET /admin/orders`
- **Name**: `admin.orders`
- **Controller**: `AdminPanelController@orders`
- **Description**: Order management interface
- **Access**: Admin and Vendor users

#### Blog Management
- **Route**: `GET /admin/blog`
- **Name**: `admin.blog`
- **Controller**: `AdminPanelController@blog`
- **Description**: Blog article management
- **Access**: Admin users only

#### Category Management
- **Route**: `GET /admin/categories`
- **Name**: `admin.categories`
- **Controller**: `AdminPanelController@categories`
- **Description**: Product category management
- **Access**: Admin users only

#### Attribute Management
- **Route**: `GET /admin/attributes`
- **Name**: `admin.attributes`
- **Controller**: `AdminPanelController@attributes`
- **Description**: Product attribute management
- **Access**: Admin users only

#### Settings
- **Route**: `GET /admin/settings`
- **Name**: `admin.settings`
- **Controller**: `AdminPanelController@settings`
- **Description**: System settings management
- **Access**: Admin users only

### CRM Form Submissions (New)

#### Form Submissions List
- **Route**: `GET /admin/form-submissions`
- **Name**: `admin.form-submissions`
- **Controller**: `AdminPanelController@formSubmissions`
- **Description**: View and manage form submissions from website
- **Access**: Admin users only
- **Livewire Component**: `admin.crm.show-all-form-submissions`

**Features**:
- Form selector to choose which form's submissions to view
- Paginated submissions list (15 per page)
- Submission preview with key information
- Real-time form switching
- Mobile-responsive interface

#### Form Submission Detail
- **Route**: `GET /admin/form-submissions/{id}`
- **Name**: `admin.form-submissions.detail`
- **Controller**: `AdminPanelController@formSubmissionDetail`
- **Description**: View detailed information for a specific form submission
- **Access**: Admin users only
- **Parameters**: 
  - `{id}` (string) - UUID of the form submission
- **Livewire Component**: `admin.crm.show-submission-detail`

**Features**:
- Complete submission details with formatted answers
- Field type-specific rendering (email, phone, URLs, etc.)
- Breadcrumb navigation
- Raw JSON data view (debug mode only)
- Back to list navigation

## Frontend Routes

### Shop Routes
- **Route**: `GET /shop/{locale?}`
- **Description**: Main shop page with product catalog
- **Parameters**: `{locale}` - Language locale (en/es)

### Product Routes
- **Route**: `GET /{locale}/product/{productSlug}`
- **Name**: `product.show.{locale}`
- **Description**: Individual product detail page
- **Parameters**: 
  - `{locale}` - Language locale
  - `{productSlug}` - Product slug identifier

### Blog Routes
- **Route**: `GET /blog`
- **Description**: Blog listing page
- **Route**: `GET /blog/{slug}`
- **Description**: Individual blog article page

## Livewire Components (AJAX Endpoints)

### CRM Form Components

#### Form Submission Component
- **Component**: `crm/forms/show`
- **Class**: `\Src\Crm\Forms\Frontend\Form`
- **Description**: Handles form submissions from frontend

#### Admin Form Submissions List
- **Component**: `admin.crm.show-all-form-submissions`
- **Class**: `\Src\Admin\Crm\Frontend\ShowAllFormSubmissions`
- **Methods**:
  - `mount()` - Initialize component with forms list
  - `updatedSelectedFormId()` - Handle form selection changes
  - `loadSubmissions()` - Load submissions for selected form
  - `nextPage()` - Navigate to next page
  - `previousPage()` - Navigate to previous page
  - `getSelectedFormName()` - Get display name of selected form

#### Admin Form Submission Detail
- **Component**: `admin.crm.show-submission-detail`
- **Class**: `\Src\Admin\Crm\Frontend\ShowSubmissionDetail`
- **Methods**:
  - `mount(string $submissionId)` - Initialize with submission ID
  - `loadSubmission()` - Load submission details
  - `getFormattedAnswers()` - Format answers for display

### Other Components

#### Product Management
- **Component**: `products/show`
- **Class**: `\Src\Admin\Product\Frontend\ShowAllProduct`
- **Description**: Admin product listing and management

#### Blog Management
- **Component**: `admin.blog.articles.show-all`
- **Class**: `\Src\Admin\Blog\Articles\Frontend\ShowAllArticle`
- **Description**: Admin blog article management

#### Cart Components
- **Component**: `cart.page` - Cart page interface
- **Component**: `cart.icon` - Cart icon with item count
- **Component**: `cart.add-to-cart-button` - Add to cart functionality

#### Search Components
- **Component**: `shared.search-bar` - Desktop search interface
- **Component**: `shared.search-bar-mobile` - Mobile search interface

#### Shared Components
- **Component**: `shared/language-selector` - Language switching
- **Component**: `shared/cookie-banner` - Cookie consent banner
- **Component**: `shared/pagination` - Pagination controls
- **Component**: `shared/whatsapp-widget` - WhatsApp contact widget

## API Response Formats

### CRM Use Cases Response Formats

#### Forms List Response
```json
[
  {
    "id": "550e8400-e29b-41d4-a716-446655440000",
    "form_name": "We Buy Your Bag",
    "form_identifier": "we_buy_your_bag",
    "submissions_count": 25
  }
]
```

#### Form Submissions Response
```json
{
  "submissions": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440001",
      "submitted_at": "January 15, 2025 at 2:30 PM",
      "preview": "John Doe - john@example.com - Vintage Chanel bag in excellent condition"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "total": 42,
    "has_more_pages": true
  }
}
```

#### Submission Detail Response
```json
{
  "id": "550e8400-e29b-41d4-a716-446655440001",
  "form_name": "We Buy Your Bag",
  "submitted_at": "2025-01-15 14:30:22",
  "answers": {
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1-555-0123",
    "bag_description": "Vintage Chanel bag in excellent condition"
  }
}
```

## Error Handling

### HTTP Status Codes
- **200 OK** - Successful request
- **302 Found** - Redirect (authentication, form submissions)
- **403 Forbidden** - Access denied (insufficient permissions)
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation error
- **500 Internal Server Error** - Server error

### Error Response Format
```json
{
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

## Rate Limiting
- Standard Laravel rate limiting applies
- Admin routes: 60 requests per minute per IP
- API routes: 100 requests per minute per IP
- Form submissions: 10 requests per minute per IP

## CSRF Protection
All POST, PUT, PATCH, and DELETE requests require CSRF tokens when using session-based authentication.

## Middleware

### Admin Middleware
- **Name**: `admin`
- **Purpose**: Restricts access to admin panel routes
- **Requirements**: Authenticated user with 'admin' role
- **Redirect**: Redirects to admin login if not authenticated

### Auth Middleware
- **Name**: `auth`  
- **Purpose**: Requires user authentication
- **Redirect**: Redirects to login page if not authenticated

### Guest Middleware
- **Name**: `guest`
- **Purpose**: Allows only non-authenticated users
- **Redirect**: Redirects authenticated users to intended page

## Development Notes

### Docker Commands
All Laravel artisan commands should be prefixed with Docker:
```bash
docker compose exec app php artisan route:list
docker compose exec app php artisan route:cache
docker compose exec app php artisan config:cache
```

### Route Caching
For production deployment:
```bash
docker compose exec app php artisan route:cache
docker compose exec app php artisan config:cache
docker compose exec app php artisan view:cache
```

### Testing Routes
```bash
docker compose exec app php artisan test --filter=RouteTest
make test
```

This API documentation covers all the current routes and endpoints in the Bags & Tea marketplace platform, with special emphasis on the newly implemented CRM form submissions management system.