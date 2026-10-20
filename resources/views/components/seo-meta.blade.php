@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'canonical' => null,
    'noIndex' => false,
    'type' => 'website',
    'structuredData' => [],
])
@php
    $settings = \App\Models\WebsiteSetting::current();
    $resolvedTitle = $title ? $title.config('ecozeen.seo.default_title_suffix') : ($settings->site_name ?: 'Ecozeen Group').' — '.$settings->tagline;
    $resolvedDescription = $description ?: $settings->supporting_statement;
    $resolvedImage = $image ?: ($settings->defaultOgImageMedia?->url());
    $resolvedCanonical = $canonical ?: url()->current();
    $shouldIndex = ! $noIndex && $settings->search_indexing_enabled;
@endphp
<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ \Illuminate\Support\Str::limit($resolvedDescription, 320) }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">
<meta name="robots" content="{{ $shouldIndex ? 'index, follow' : 'noindex, nofollow' }}">

<meta property="og:site_name" content="{{ $settings->site_name }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($resolvedDescription, 320) }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
@if ($resolvedImage)
    <meta property="og:image" content="{{ $resolvedImage }}">
@endif

<meta name="twitter:card" content="{{ $resolvedImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($resolvedDescription, 320) }}">
@if ($resolvedImage)
    <meta name="twitter:image" content="{{ $resolvedImage }}">
@endif

@if ($settings->faviconMedia)
    <link rel="icon" href="{{ $settings->faviconMedia->url() }}">
@endif

@if (! empty($structuredData))
    <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif
