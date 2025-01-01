<div class="hidden md:grid bg-theme-color-2 text-white pt-12 mt-4">
    <nav class="items-center justify-center">
        <ul class="flex justify-center space-x-8 text-xl font-medium">
            <li><a href="/shop" class="text-text-color-1 hover:text-gray-900">Shop</a></li>
            <li><a href="/blog" class="text-text-color-1 hover:text-gray-900">Blog</a></li>
            <li><a href="/contact" class="text-text-color-1 hover:text-gray-900">Contact</a></li>
        </ul>
    </nav>
    <hr class="border-t-2 border-text-color-1 my-4 w-1/2 mx-auto my-8">
    <nav class="items-center justify-center">
        <ul class="flex justify-center space-x-8 text-xl font-medium">
            <li><a href="/shop" class="text-text-color-1 hover:text-gray-900">Barcelona</a></li>
        </ul>
    </nav>
    <hr class="border-t-2 border-text-color-1 my-4 w-1/2 mx-auto my-8">
    <div class="flex items-center justify-center space-x-2">
        <img src="{{ asset('images/icons/icon_location.svg') }}" alt="Icon" class="w-8 h-8">
        <span class="text-sm underline">Apertura próximamente</span>
    </div>
    <ul class="flex justify-center space-x-8 text-sm underline font-light my-4">
        <li class="flex items-center space-x-2">
            <img src="{{ asset('images/icons/icon_phone.svg') }}" alt="Icon" class="w-8 h-6">
            <a href="tel:+34606986218" class="text-text-color-1 hover:text-gray-900">+34 606 986 218</a>
        </li>
        <li class="flex items-center space-x-2">
            <img src="{{ asset('images/icons/icon_email.svg') }}" alt="Icon" class="w-8 h-8">
            <a href="mailto:info@bolsosluxe.com" class="text-text-color-1 hover:text-gray-900">info@bolsosluxe.com</a>
        </li>
    </ul>
    <ul class="flex justify-center space-x-8 text-sm underline font-light mt-8 mb-8">
        <li><a href="/privacy-policy" class="text-text-color-1 hover:text-gray-900">Política de privacidad</a></li>
        <li><a href="/cookies-policy" class="text-text-color-1 hover:text-gray-900">Política de cookies</a></li>
    </ul>
    <p class="text-xs mx-auto mb-2">&copy; {{ date('Y') }} Bolsos Luxe. All rights reserved.</p>
</div>
