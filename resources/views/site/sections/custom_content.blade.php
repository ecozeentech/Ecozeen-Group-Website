<section class="section-spacing">
    <div class="container-site max-w-4xl">
        @if (! empty($settings['heading']))
            <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
        @endif
        @if (! empty($settings['content']))
            <div class="prose prose-slate mt-4 max-w-none">{!! $settings['content'] !!}</div>
        @endif
    </div>
</section>
