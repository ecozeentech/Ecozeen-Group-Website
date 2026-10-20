<section class="section-spacing">
    <div class="container-site grid grid-cols-1 gap-8 lg:grid-cols-2">
        @if (! empty($settings['mission_heading']) || ! empty($settings['mission_text']))
            <div class="card p-8">
                <h3 class="font-heading text-xl font-bold text-ink">{{ $settings['mission_heading'] ?? 'Our Mission' }}</h3>
                <div class="prose prose-slate mt-3 max-w-none">{!! $settings['mission_text'] ?? '' !!}</div>
            </div>
        @endif
        @if (! empty($settings['vision_heading']) || ! empty($settings['vision_text']))
            <div class="card p-8">
                <h3 class="font-heading text-xl font-bold text-ink">{{ $settings['vision_heading'] ?? 'Our Vision' }}</h3>
                <div class="prose prose-slate mt-3 max-w-none">{!! $settings['vision_text'] ?? '' !!}</div>
            </div>
        @endif
    </div>
</section>
