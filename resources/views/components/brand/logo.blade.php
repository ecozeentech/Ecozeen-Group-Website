@props(['settings' => null, 'dark' => false])
@php
    $settings = $settings ?? \App\Models\WebsiteSetting::current();
    $logo = $dark ? ($settings->logoDarkMedia ?: $settings->logoMedia) : $settings->logoMedia;
    $siteName = $settings->site_name ?: 'Ecozeen Group';
    $fallback = $dark
        ? asset('brand/ecozeen-mark.png')
        : asset('brand/ecozeen-group-logo.png');
    $src = $logo?->url() ?: $fallback;
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    <img
        src="{{ $src }}"
        alt="{{ $siteName }}"
        class="{{ $dark ? 'h-8 w-auto brightness-0 invert' : 'h-9 w-auto sm:h-10' }}"
    >
</span>
