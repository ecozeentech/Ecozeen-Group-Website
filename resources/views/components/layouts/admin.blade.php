@props(['title' => 'Dashboard', 'subtitle' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Ecozeen Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('brand/ecozeen-mark.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-surface-canvas text-ink" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <x-admin.sidebar />

        <div
            x-show="sidebarOpen"
            x-cloak
            class="fixed inset-0 z-40 bg-slate-900/50 lg:hidden"
            @click="sidebarOpen = false"
        ></div>
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition
            class="fixed inset-y-0 left-0 z-50 w-[260px] lg:hidden"
        >
            <x-admin.sidebar />
        </div>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b border-surface-border bg-white px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" class="rounded-token p-2 text-ink lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle admin navigation">
                        <x-icon name="menu" />
                    </button>
                    <div>
                        <p class="font-heading text-base font-bold text-ink">{{ $title ?? 'Dashboard' }}</p>
                        @if (isset($subtitle))
                            <p class="text-xs text-ink-muted">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @can('pages.create')
                        <a href="{{ route('admin.pages.create') }}" class="btn-secondary hidden sm:inline-flex">
                            <x-icon name="add_circle" class="text-[18px]" />
                            New Page
                        </a>
                    @endcan
                    <a href="{{ route('home') }}" target="_blank" class="hidden text-sm font-medium text-ink-muted hover:text-brand-blue sm:inline">
                        View site
                    </a>
                    <div class="hidden items-center gap-2 rounded-lg border border-surface-border px-2 py-1.5 sm:flex">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-navy text-xs font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <div class="pr-1">
                            <p class="text-xs font-semibold text-ink">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] uppercase tracking-wide text-ink-muted">{{ auth()->user()->roles->first()?->name ?? 'Administrator' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-outline text-sm">Log out</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-6">
                @if (session('success'))
                    <div class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-token border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
