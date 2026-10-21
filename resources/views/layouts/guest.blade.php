<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ecozeen Group') }} — Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('brand/ecozeen-mark.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-canvas text-ink antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        <aside class="relative hidden overflow-hidden bg-brand-navy p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.45),transparent_55%)]"></div>
            <div class="relative">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 p-1.5">
                        <x-brand.logo-mark class="h-8 w-8 brightness-0 invert" />
                    </span>
                    <span class="font-heading text-lg font-bold">Ecozeen Group</span>
                </a>
            </div>
            <div class="relative max-w-md space-y-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-emerald-300">Enterprise CMS</p>
                <h1 class="font-heading text-4xl font-extrabold leading-tight">Secure access to the holding portal.</h1>
                <p class="text-sm leading-relaxed text-slate-300">Publish pages, manage the business directory, and route institutional inquiries from one governed workspace.</p>
            </div>
            <p class="relative text-xs text-slate-400">Authorized administrators only. All activity is logged.</p>
        </aside>

        <div class="flex items-center justify-center px-4 py-10">
            <div class="w-full max-w-md">
                <div class="mb-8 flex flex-col items-center gap-3 text-center lg:items-start lg:text-left">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 lg:hidden">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-navy p-1.5">
                            <x-brand.logo-mark class="h-7 w-7 brightness-0 invert" />
                        </span>
                        <span class="font-heading text-xl font-bold text-ink">Ecozeen Group</span>
                    </a>
                    <div>
                        <p class="font-heading text-2xl font-bold text-ink">Admin sign in</p>
                        <p class="mt-1 text-sm text-ink-muted">Use your issued administrator credentials.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-surface-border bg-white p-8 shadow-level2">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-ink-muted lg:text-left">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1 font-medium hover:text-brand-blue">&larr; Back to the website</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
