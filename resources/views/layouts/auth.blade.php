<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Updat3r') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/template.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/circular-std/style.css') }}" rel="stylesheet">
    <style>
        body {
            padding-top: 40px;
        }
    </style>
</head>

<body>
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center">
                <p class="navbar-brand">{{ config('app.name', 'Updat3r') }}</p>
                <span class="splash-description">@yield('splash-description')</span>
            </div>
            <div class="card-body">
                @yield('content')
            </div>
            <div class="card-footer bg-white p-0">
                @hasSection('switch-button')
                    <div class="card-footer-item card-footer-item-bordered">
                        @yield('switch-button')
                    </div>
                @endif
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="/about" class="footer-link">What is {{ config('app.name', 'Updat3r') }}?</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
