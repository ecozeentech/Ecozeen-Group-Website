<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ecozeen Group') }} — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-surface-canvas px-4 py-10">
    <div class="w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center gap-2 text-center">
            <a href="{{ route('home') }}" class="font-heading text-xl font-bold text-ink">Ecozeen Group</a>
            <p class="text-sm text-ink-muted">Admin Dashboard</p>
        </div>

        <div class="card p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-xs text-ink-muted">
            <a href="{{ route('home') }}" class="hover:text-brand-blue">&larr; Back to the website</a>
        </p>
    </div>
</body>
</html>
