<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Employee Management System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="welcome-page">
    <header class="welcome-navbar">
        <a href="{{ route('login') }}" aria-label="{{ config('app.name', 'Employee Management System') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'Employee Management System') }}">
        </a>
        <a href="{{ route('login') }}" class="btn btn-primary">Sign in</a>
    </header>
    <main class="welcome-hero">
        <div class="welcome-copy">
            <p class="page-eyebrow">People-first workforce management</p>
            <h1>A better way to manage your people.</h1>
            <p>Bring employee information, departments, and everyday workforce operations together in one organized workspace.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Go to your workspace</a>
        </div>
    </main>
</body>
</html>
