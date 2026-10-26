<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-11 col-sm-8 col-md-5 col-lg-4">
                <h4 class="text-center mb-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" height="32" class="me-2">{{ $appName }}
                </h4>
                @include('partials.alerts')
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
