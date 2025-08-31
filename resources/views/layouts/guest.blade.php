<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-50 text-gray-800">

    <div class="min-h-screen flex flex-col justify-center items-center">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
