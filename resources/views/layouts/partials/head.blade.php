<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name'))</title>

<meta name="description" content="@yield('meta_description', 'Default blog meta description.')">

<link rel="icon" href="{{ asset('favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
{{-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> --}}
@vite('resources/css/app.css')
