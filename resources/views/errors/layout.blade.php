<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') | {{ $appName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="error-page">
        <section class="error-card">
            <a href="{{ route('login') }}" aria-label="{{ $appName }}">
                <img src="{{ asset('images/logo.svg') }}" alt="{{ $appName }}" width="144" height="40" class="mb-4">
            </a>
            <p class="page-eyebrow">{{ __('messages.something_needs_attention') }}</p>
            <h1 class="error-code">@yield('code')</h1>
            <p class="text-secondary fs-5 mt-3 mb-4">@yield('message')</p>
            @if (auth()->user()?->role->value === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">{{ __('messages.back_to_dashboard') }}</a>
            @elseif (auth()->user()?->role->value === 'employee')
                <a href="{{ route('profile.show', auth()->user()?->employee?->id) }}" class="btn btn-primary">{{ __('messages.back_to_profile') }}</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.go_to_sign_in') }}</a>
            @endif
        </section>
    </main>
</body>
</html>
