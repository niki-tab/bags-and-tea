# Notification System Documentation

## Overview
This document describes the email notification system implemented using SendGrid, including order confirmation emails with professional header/footer templates.

## Features Implemented

### 1. SendGrid Integration
- **Package**: `sendgrid/sendgrid` v8.1.2
- **Configuration**: SMTP transport via SendGrid
- **API Key**: Configured in environment variables
- **From Address**: `info@bagsandtea.com`

### 2. Email Infrastructure

#### Base Email Layout
- **File**: `resources/views/emails/layouts/base.blade.php`
- **Features**:
  - Responsive design (mobile-friendly)
  - Email-client optimized CSS
  - Brand colors (`#482626`, `#F8F3F0`)
  - Consistent typography (Arial font family)

#### Email Header Component
- **File**: `resources/views/emails/partials/header.blade.php`
- **Features**:
  - Vertically centered logo using line-height technique
  - Brand-consistent background colors
  - Dynamic email title section
  - Email-optimized image attributes

#### Email Footer Component
- **File**: `resources/views/emails/partials/footer.blade.php`
- **Features**:
  - Contact information section
  - Social media links (placeholder)
  - Copyright and legal text
  - Unsubscribe/Privacy policy links
  - Professional disclaimer section

### 3. Order Confirmation Email
- **Mailable**: `app/Mail/OrderConfirmation.php`
- **Template**: `resources/views/emails/orders/order/order-confirmation.blade.php`
- **Features**:
  - Personalized greeting
  - Order number and date display
  - Placeholder for order details
  - Next steps guidance
  - Call-to-action button
  - Professional styling

### 4. Testing Infrastructure
- **Basic Test Endpoint**: `/api/test-email`
- **Order Confirmation Test**: `/api/test-order-confirmation`
- **Parameters**:
  - `email`: Recipient email address
  - `order`: Order number
  - `name`: Customer name
  - `message`: Custom message (for basic test)

## Configuration

### Environment Variables
```bash
# SendGrid Configuration
SENDGRID_API_KEY=your_sendgrid_api_key_here

# Mail Configuration
MAIL_MAILER=sendgrid
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD="${SENDGRID_API_KEY}"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@bagsandtea.com
MAIL_FROM_NAME="Bags And Tea"
```

### Mail Configuration
- **File**: `config/mail.php`
- **Added SendGrid mailer configuration**:
```php
'sendgrid' => [
    'transport' => 'smtp',
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'apikey',
    'password' => env('SENDGRID_API_KEY'),
    'timeout' => null,
],
```

## Technical Insights & Solutions

### Email Client Compatibility
1. **SVG Images**: Email clients often block SVG images
   - **Solution**: Use PNG/JPG formats
   - **Implementation**: Updated logo to use `.png` extension

2. **Vertical Centering**: Complex in email templates
   - **Solution**: Line-height technique with fixed container height
   - **Implementation**: `height: 100px; line-height: 100px` with `vertical-align: middle`

3. **Responsive Design**: Email CSS differs from web CSS
   - **Solution**: Table-based layouts with inline styles
   - **Implementation**: Nested tables with `max-width` and media queries

### Image Optimization for Emails
- **Explicit dimensions**: `width="150" height="60"`
- **Border removal**: `border="0"` and `outline: none`
- **Display properties**: `display: inline-block` for proper centering
- **Alt text**: Fallback for blocked images

### Email Spam Prevention
- **Consistent branding**: Professional header/footer
- **Unsubscribe links**: Required for marketing emails
- **From address**: Matches domain (`bagsandtea.com`)
- **HTML structure**: Clean, table-based layout

## API Endpoints

### Basic Email Test
```http
GET /api/test-email
GET /api/test-email?email=test@example.com&message=Custom message
```

**Response**:
```json
{
    "success": true,
    "message": "Test email sent successfully!",
    "sent_to": "test@example.com",
    "email_message": "Custom message"
}
```

### Order Confirmation Test
```http
GET /api/test-order-confirmation
GET /api/test-order-confirmation?email=customer@example.com&order=%23ORD-123&name=John Doe
```

**Response**:
```json
{
    "success": true,
    "message": "Order confirmation email sent successfully!",
    "sent_to": "customer@example.com",
    "order_number": "#ORD-123",
    "customer_name": "John Doe"
}
```

## File Structure
```
app/Mail/
├── TestEmail.php              (Basic test email)
└── OrderConfirmation.php      (Order confirmation email)

resources/views/emails/
├── layouts/
│   └── base.blade.php         (Main email layout)
├── partials/
│   ├── header.blade.php       (Email header component)
│   └── footer.blade.php       (Email footer component)
├── orders/order/
│   └── order-confirmation.blade.php  (Order confirmation template)
└── test.blade.php            (Basic test email template)

routes/
└── api.php                   (Test endpoints)

config/
└── mail.php                 (Mail configuration)

.env                         (Environment variables)
```

## Future Enhancements
1. **Order Details Integration**: Add actual order items, pricing, shipping info
2. **Email Templates**: Welcome emails, password reset, shipping confirmations
3. **Queue System**: Background email processing for large volumes
4. **Email Analytics**: Track open rates, click-through rates
5. **Template Variations**: Different templates for different order types
6. **Multi-language Support**: Email templates in Spanish/English

## Testing Notes
- Test emails may appear in spam/junk folders initially
- Use production email addresses for accurate testing
- SendGrid provides delivery analytics and reputation monitoring
- Consider domain authentication (SPF, DKIM) for production use

## Troubleshooting
- **Logo not displaying**: Check image URL accessibility and format
- **Layout issues**: Verify table structure and inline styles
- **Delivery problems**: Check SendGrid API key and domain reputation
- **Mobile display**: Test on various email clients and devices