<!DOCTYPE html>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> --}}
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>

<body class="bg-gray-100">

    @include('layouts.partials.navbar')


    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu functionality with null check
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

        });

        // Additional error handling for window errors
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
        });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>

</body>

</html>
