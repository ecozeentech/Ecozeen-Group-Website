@props(['title' => 'Dashboard', 'subtitle' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Ecozeen Admin</title>
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
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                    </button>
                    <div>
                        <p class="font-heading text-base font-bold text-ink">{{ $title ?? 'Dashboard' }}</p>
                        @if (isset($subtitle))
                            <p class="text-xs text-ink-muted">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden text-sm font-medium text-ink-muted hover:text-brand-blue sm:inline">
                        View site &rarr;
                    </a>
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
