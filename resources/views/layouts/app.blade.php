<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite('resources/css/app.css') <!-- Vite loading CSS -->
    @vite('resources/js/app.js') <!-- Vite loading JS -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="alternate" href="{{ url()->current() }}" hreflang="{{app()->getLocale()}}">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset("images/icons/favicon.ico") }}" type="image/x-icon">
    <meta name="google-site-verification" content="8Zu5brHcw0lALxaJKOZYyom4xhWaB9sQosfv9xwyfes" />
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-white">
    @livewireScripts
    <header class="z-10">
        <x-header-desktop/>
        <x-header-mobile/>
    </header>

    <main class="flex-grow container mx-auto z-0 mt-24">
        @yield('content')
    </main>

    <footer class="mt-12">
        <x-footer-desktop />
        <x-footer-mobile />
    </footer>
</body>
</html>