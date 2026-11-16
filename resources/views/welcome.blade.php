<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', __('messages.employee_management')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="welcome-page">
    <header class="welcome-navbar">
        <a href="{{ route('login') }}" aria-label="{{ config('app.name', __('messages.employee_management')) }}">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', __('messages.employee_management')) }}">
        </a>
        <a href="{{ route('login') }}" class="btn btn-primary">{{ __('messages.sign_in') }}</a>
    </header>
    <main class="welcome-hero">
        <div class="welcome-copy">
            <p class="page-eyebrow">{{ __('messages.people_first_workforce_management') }}</p>
            <h1>{{ __('messages.better_way_to_manage_people') }}</h1>
            <p>{{ __('messages.welcome_workspace_description') }}</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">{{ __('messages.go_to_workspace') }}</a>
        </div>
    </main>
</body>
</html>
