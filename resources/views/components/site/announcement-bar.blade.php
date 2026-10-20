@props(['settings'])
@if ($settings->announcement_enabled && $settings->announcement_text)
    <div class="bg-brand-navy px-4 py-2 text-center text-xs font-medium text-white sm:text-sm">
        {{ $settings->announcement_text }}
    </div>
@endif
