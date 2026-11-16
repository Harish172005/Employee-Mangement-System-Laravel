<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <main class="auth-shell">
        <aside class="auth-brand-panel">
            <a href="{{ route('login') }}" aria-label="{{ $appName }}">
                <img src="{{ asset('images/logo.svg') }}" alt="{{ $appName }}">
            </a>
            <div class="auth-brand-copy">
                <h1>{{ __('messages.guest_heading') }}</h1>
                <p>{{ __('messages.guest_description') }}</p>
            </div>
            <small class="text-white-50">&copy; {{ date('Y') }} {{ $appName }}</small>
        </aside>
        <section class="auth-main">
            <div class="auth-content">
                <a class="auth-mobile-brand" href="{{ route('login') }}" aria-label="{{ $appName }}">
                    <img src="{{ asset('images/logo.svg') }}" alt="{{ $appName }}">
                </a>
                @include('partials.alerts')
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>
