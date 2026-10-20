@php
    $style = $settings['style'] ?? 'solid';
    $wrapperClass = $style === 'subtle' ? 'bg-surface-canvas' : 'bg-brand-navy text-white';
@endphp
<section class="section-spacing {{ $wrapperClass }}">
    <div class="container-site flex flex-col items-center gap-5 text-center">
        @if (! empty($settings['heading']))
            <h2 class="font-heading text-2xl font-bold sm:text-3xl">{{ $settings['heading'] }}</h2>
        @endif
        @if (! empty($settings['subheading']))
            <p class="max-w-xl {{ $style === 'subtle' ? 'text-ink-muted' : 'text-slate-200' }}">{{ $settings['subheading'] }}</p>
        @endif
        @if (! empty($settings['primary_label']))
            <a href="{{ $settings['primary_url'] ?: route('contact') }}" class="btn-secondary">{{ $settings['primary_label'] }}</a>
        @endif
    </div>
</section>
