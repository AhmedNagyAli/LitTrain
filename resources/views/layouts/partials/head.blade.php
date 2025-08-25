<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', config('app.name'))</title>

<meta name="description" content="@yield('meta_description', 'Default blog meta description.')">
@vite('resources/css/app.css')
{{-- <link rel="icon" href="{{ asset('favicon.ico') }}"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@push('styles')
 <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .navbar-shadow {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .writing-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: calc(100vh - 4rem);
        }
        .writing-prompt {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #8B5CF6;
        }
        .writing-area {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .character-count {
            color: #6D28D9;
            font-weight: 500;
        }
        .menu-transition {
            transition: all 0.3s ease;
        }
    </style>
@endpush
{{-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> --}}

