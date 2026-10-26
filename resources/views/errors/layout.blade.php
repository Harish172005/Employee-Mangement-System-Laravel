<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') | Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center" style="margin-top: 12%;">
    <h1 class="display-1 fw-bold">@yield('code')</h1>
    <p class="fs-5">@yield('message')</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Back to home</a>
</body>
</html>
