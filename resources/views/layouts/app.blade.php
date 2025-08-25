<!DOCTYPE html>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> --}}
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">

    @include('layouts.partials.navbar')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @yield('script')
    @stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
