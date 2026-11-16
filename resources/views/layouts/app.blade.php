<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-light">
    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white border-end p-0 sidebar">
                @include('partials.sidebar')
            </div>
            <main class="col-md-10 p-4">
                @include('partials.alerts')
                <h3 class="mb-3">@yield('heading')</h3>
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.footer')
    @include('partials.confirm-modal')
    @stack('scripts')
</body>
</html>
