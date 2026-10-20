<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ecozeen Group') }} — Account</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-canvas text-ink">
    <header class="flex h-16 items-center justify-between border-b border-surface-border bg-white px-4 lg:px-6">
        <a href="{{ route('home') }}" class="font-heading text-base font-bold text-ink">Ecozeen Group</a>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="hidden text-sm font-medium text-ink-muted hover:text-brand-blue sm:inline">
                    Admin dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline text-sm">Log out</button>
                </form>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-10 lg:px-6">
        @isset($header)
            <div class="mb-6">{{ $header }}</div>
        @endisset

        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" role="status">
                Saved.
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
