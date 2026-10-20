<section class="section-spacing">
    <div class="container-site max-w-2xl">
        <div class="text-center">
            @if (! empty($settings['heading']))
                <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            @if (! empty($settings['subheading']))
                <p class="mt-3 text-ink-muted">{{ $settings['subheading'] }}</p>
            @endif
        </div>

        @if (session('status'))
            <p class="mt-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            @csrf
            <input type="hidden" name="inquiry_type" value="general">
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div>
                <label for="section-contact-name" class="form-label">Full name</label>
                <input id="section-contact-name" type="text" name="full_name" required class="form-input">
                @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="section-contact-email" class="form-label">Email</label>
                <input id="section-contact-email" type="email" name="email" required class="form-input">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="section-contact-message" class="form-label">Message</label>
                <textarea id="section-contact-message" name="message" rows="4" required class="form-textarea"></textarea>
                @error('message')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="flex items-center gap-2 text-xs text-ink-muted">
                    <input type="checkbox" name="consent" value="1" required class="rounded border-slate-300">
                    I consent to being contacted about this enquiry.
                </label>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="btn-primary w-full">Send Message</button>
            </div>
        </form>
    </div>
</section>
