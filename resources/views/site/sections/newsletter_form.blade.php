<section class="section-spacing bg-surface-canvas">
    <div class="container-site max-w-2xl text-center">
        @if (! empty($settings['heading']))
            <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
        @endif
        @if (! empty($settings['subheading']))
            <p class="mt-3 text-ink-muted">{{ $settings['subheading'] }}</p>
        @endif

        @if (session('status'))
            <p class="mt-4 text-sm font-medium text-brand-emerald">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-6 flex flex-col items-center gap-3">
            @csrf
            <div class="flex w-full flex-col gap-3 sm:flex-row">
                <label for="section-newsletter-email" class="sr-only">Email address</label>
                <input id="section-newsletter-email" type="email" name="email" required placeholder="name@institution.com" class="form-input flex-1">
                <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                <button type="submit" class="btn-primary whitespace-nowrap">Subscribe</button>
            </div>
            <label class="flex items-center gap-2 text-xs text-ink-muted">
                <input type="checkbox" name="consent" value="1" required class="rounded border-slate-300">
                I consent to receive Ecozeen Group email updates.
            </label>
        </form>
        @error('email')
            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>
</section>
