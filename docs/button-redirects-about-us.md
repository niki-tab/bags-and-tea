# About Us Page - Circular Fashion Button Redirects

## Overview

This document describes the implementation of functional redirects for the "Circular Fashion" section buttons on the About Us page, improving user navigation and site engagement.

## Feature Description

The "CIRCULAR FASHION IS TODAY'S TREND" section on the About Us page contains two prominent buttons that now redirect users to relevant pages:

1. **"Sell Your Bag" Button** → We Buy Your Bag page
2. **"Buy Your Bag" Button** → Shop page

## Implementation

### Location
- **File**: `resources/views/pages/about-us/show.blade.php`
- **Section**: Lines 163-170 (Circular Fashion banner section)

### Before (Non-functional Buttons)
```html
<button class="bg-[#482626] text-white px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 transition whitespace-nowrap">
    {{ __('pages/about-us.sell_your_bag_button') }}
</button>
<button class="border border-[#B92334] text-[#B92334] px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 hover:text-white transition whitespace-nowrap">
    {{ __('pages/about-us.buy_your_bag_button') }}
</button>
```

### After (Functional Link Buttons)
```html
<a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="bg-[#482626] text-white px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 transition whitespace-nowrap">
    {{ __('pages/about-us.sell_your_bag_button') }}
</a>
<a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="border border-[#B92334] text-[#B92334] px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 hover:text-white transition whitespace-nowrap">
    {{ __('pages/about-us.buy_your_bag_button') }}
</a>
```

## Button Details

### 1. Sell Your Bag Button

**Visual Design:**
- Dark background (`bg-[#482626]`)
- White text
- Rounded corners and padding
- Hover effects with background color change

**Functionality:**
- **Spanish**: Redirects to `we-buy-your-bag.show.es`
- **English**: Redirects to `we-buy-your-bag.show.en`
- Includes proper `locale` parameter for internationalization

**Route Logic:**
```php
route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()])
```

### 2. Buy Your Bag Button

**Visual Design:**
- Outlined style with border
- Brand color border (`border-[#B92334]`)
- Text color matches border
- Hover effects with background fill

**Functionality:**
- **Spanish**: Redirects to `shop.show.es`
- **English**: Redirects to `shop.show.en`
- Includes proper `locale` parameter for internationalization

**Route Logic:**
```php
route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()])
```

## Section Context

### Visual Layout
The buttons appear in the "Circular Fashion" banner section of the About Us page:

```html
<div class="bg-[#EEC0C3] py-8 px-2 md:px-0">
    <h2 class="text-4xl font-['Lovera'] text-[#482626] text-center tracking-widest mb-6">
        {{ __('pages/about-us.circular_fashion_title') }}
    </h2>
    <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-6">
        <!-- Buttons here -->
    </div>
</div>
```

### Responsive Design
- **Mobile**: Buttons stack vertically (`flex-col`)
- **Desktop**: Buttons display horizontally (`md:flex-row`)
- **Spacing**: 4-unit gap between buttons
- **Alignment**: Centered on page

## Internationalization

### Language Support
The implementation supports both English and Spanish:

**Spanish URLs:**
- Sell button: `/es/compramos-tu-bolso` (we-buy-your-bag.show.es)
- Buy button: `/es/tienda` (shop.show.es)

**English URLs:**
- Sell button: `/en/we-buy-your-bag` (we-buy-your-bag.show.en)
- Buy button: `/en/shop` (shop.show.en)

### Translation Keys
Button text uses translation keys from the language files:
- `pages/about-us.sell_your_bag_button`
- `pages/about-us.buy_your_bag_button`

## Route Dependencies

### Required Routes
The implementation depends on these routes being properly defined:

1. **We Buy Your Bag Routes:**
   - `we-buy-your-bag.show.es`
   - `we-buy-your-bag.show.en`

2. **Shop Routes:**
   - `shop.show.es`
   - `shop.show.en`

### Route Parameters
All routes must accept the `locale` parameter:
```php
Route::get('{locale}/tienda', [ShopController::class, 'show'])->name('shop.show.es');
Route::get('{locale}/shop', [ShopController::class, 'show'])->name('shop.show.en');
```

## User Experience Impact

### Before Implementation
- Buttons were purely decorative
- No user action possible
- Missed conversion opportunities
- Poor user experience with non-functional UI elements

### After Implementation
- Clear call-to-action functionality
- Seamless navigation to relevant pages
- Improved conversion funnel
- Better user engagement and site exploration

## Technical Benefits

1. **SEO Improvement**: Proper internal linking structure
2. **User Flow**: Better navigation between related sections
3. **Conversion Optimization**: Direct paths to key business actions
4. **Accessibility**: Semantic HTML with proper link elements
5. **Maintenance**: Uses existing route system and translations

## Testing Considerations

### Manual Testing Checklist
- [ ] Spanish "Sell Your Bag" button redirects to Spanish we-buy-your-bag page
- [ ] English "Sell Your Bag" button redirects to English we-buy-your-bag page
- [ ] Spanish "Buy Your Bag" button redirects to Spanish shop page
- [ ] English "Buy Your Bag" button redirects to English shop page
- [ ] Button styling remains consistent after conversion to links
- [ ] Hover effects work properly
- [ ] Mobile responsive layout functions correctly

### Browser Testing
- Test across major browsers (Chrome, Firefox, Safari, Edge)
- Verify mobile and desktop functionality
- Check hover states and transitions

## Future Enhancements

- Add analytics tracking to measure button click-through rates
- Consider A/B testing button text or styling for conversion optimization
- Add loading states or transitions for better UX
- Implement event tracking for user behavior analysis