<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.dashboard')) | {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    @auth
        @if (auth()->user()?->role->value  === 'admin')
            @include('partials.admin-navbar')
        @elseif (auth()->user()?->role->value === 'employee')
            @include('partials.employee-navbar')
        @endif
    @endauth

    <div class="language-switcher">
    <span class="language-label">
        🌐 {{ __('messages.language') }}
    </span>

    <div class="language-options">
        <a
            href="{{ route('language.switch', 'en') }}"
            class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}"
        >
            English
        </a>

        <a
            href="{{ route('language.switch', 'ta') }}"
            class="language-option {{ app()->getLocale() === 'ta' ? 'active' : '' }}"
        >
            தமிழ்
        </a>
    </div>
</div>

    <main class="app-main">
        @include('partials.alerts')
        @hasSection('heading')
            <header class="page-header">
                <div class="page-eyebrow">{{ $appName }}</div>
                <h1 class="page-title">@yield('heading')</h1>
            </header>
        @endif
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.confirm-modal')
    @stack('scripts')
</body>
</html>
