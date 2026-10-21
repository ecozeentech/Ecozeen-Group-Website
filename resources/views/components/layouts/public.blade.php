@props([
    'seoTitle' => null,
    'seoDescription' => null,
    'seoImage' => null,
    'seoCanonical' => null,
    'noIndex' => false,
    'seoType' => 'website',
    'structuredData' => [],
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('ecozeen-theme');
                var dark = saved === 'dark' || (saved !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
            } catch (e) {}
        })();
    </script>

    <x-seo-meta
        :title="$seoTitle"
        :description="$seoDescription"
        :image="$seoImage"
        :canonical="$seoCanonical"
        :no-index="$noIndex"
        :type="$seoType"
        :structured-data="$structuredData"
    />

    <x-theme-variables />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-surface-canvas text-ink">
    @php($settings = \App\Models\WebsiteSetting::current())

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-token focus:bg-white focus:px-4 focus:py-2 focus:shadow-level3">
        Skip to main content
    </a>

    <x-site.announcement-bar :settings="$settings" />
    <x-site.header :settings="$settings" />

    @if (session('status'))
        <div class="container-site mt-4" role="status">
            <div class="rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        </div>
    @endif

    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    <x-site.footer :settings="$settings" />

    <button
        type="button"
        class="fixed bottom-6 right-6 z-40 flex h-11 w-11 items-center justify-center rounded-full bg-brand-navy text-white shadow-level3 transition hover:bg-slate-800"
        x-data="{ show: false }"
        x-init="window.addEventListener('scroll', () => { show = window.scrollY > 400 })"
        x-show="show"
        x-cloak
        x-transition
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        aria-label="Back to top"
    >
        <x-icon name="arrow_upward" class="text-[20px]" />
    </button>

    @stack('scripts')
</body>
</html>
