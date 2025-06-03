<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>Admin Login - {{ config('app.name', 'Bags & Tea') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
        }
        
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        .brand-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="h-screen">
    <div class="h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Login Card -->
            <div class="login-card rounded-2xl p-6 sm:p-8 space-y-6">
                <!-- Header -->
                <div class="text-center">
                    <!-- Logo -->
                    <div class="mx-auto h-12 w-auto flex justify-center mb-4">
                        <img class="h-12 w-auto filter drop-shadow-md" src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" alt="Bags & Tea">
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-bold brand-gradient mb-2">
                        Admin Panel
                    </h1>
                    <p class="text-gray-600 text-sm">
                        Welcome back! Please sign in to your account.
                    </p>
                </div>

                <!-- Login Form Component -->
                @livewire('admin.auth.login')

                <!-- Footer -->
                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', 'Bags & Tea') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Auto-hide flash messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>