@php
    $maxWidth = ['narrow' => 'max-w-2xl', 'default' => 'max-w-4xl', 'wide' => 'max-w-6xl'][$settings['max_width'] ?? 'default'] ?? 'max-w-4xl';
    $align = ($settings['alignment'] ?? 'left') === 'center' ? 'text-center mx-auto' : '';
@endphp
<section class="section-spacing">
    <div class="container-site {{ $maxWidth }} {{ $align }}">
        @if (! empty($settings['heading']))
            <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
        @endif
        @if (! empty($settings['content']))
            <div class="prose prose-slate mt-4 max-w-none">{!! $settings['content'] !!}</div>
        @endif
    </div>
</section>
