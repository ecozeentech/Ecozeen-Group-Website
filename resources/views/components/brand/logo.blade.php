@props(['settings' => null, 'dark' => false])
@php
    $settings = $settings ?? \App\Models\WebsiteSetting::current();
    $logo = $dark ? $settings->logoDarkMedia : $settings->logoMedia;
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-heading font-extrabold text-lg']) }}>
    @if ($logo)
        <img src="{{ $logo->url() }}" alt="{{ $settings->site_name }}" class="h-8 w-auto">
    @else
        <x-brand.logo-mark class="h-8 w-8 {{ $dark ? 'text-white' : 'text-brand-navy' }}" />
        <span class="{{ $dark ? 'text-white' : 'text-ink' }}">{{ $settings->site_name ?: 'Ecozeen Group' }}</span>
    @endif
</span>
