@php
    $settings = \App\Models\WebsiteSetting::current();
@endphp
<x-layouts.public seo-title="Contact" seo-description="Get in touch with Ecozeen Group.">
    <x-site.page-hero
        eyebrow="Official communications"
        title="Contact &amp; Institutional Inquiries"
        description="Reach the relevant Ecozeen Group team. We respond to every verified enquiry."
    />

    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="space-y-4 text-sm text-ink">
                @if ($settings->contact_email)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Email</p>
                        <a href="mailto:{{ $settings->contact_email }}" class="mt-1 block font-medium text-brand-blue hover:underline">{{ $settings->contact_email }}</a>
                    </div>
                @endif
                @if ($settings->contact_phone)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Phone</p>
                        <p class="mt-1 font-medium">{{ $settings->contact_phone }}</p>
                    </div>
                @endif
                @if ($settings->address_primary)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Address</p>
                        <p class="mt-1 font-medium">{{ $settings->address_primary }}</p>
                    </div>
                @endif
                @if (! $settings->contact_email && ! $settings->contact_phone && ! $settings->address_primary)
                    <p class="text-ink-muted">Use the form to send a message. Contact details can be added by an administrator in Website Settings.</p>
                @endif
            </div>

            <div class="lg:col-span-2">
                <div class="card p-6 sm:p-8">
                    @if (session('status'))
                        <p class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="form-public grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @csrf
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <div class="sm:col-span-2">
                            <label for="contact-inquiry-type" class="form-label">Inquiry type</label>
                            <select id="contact-inquiry-type" name="inquiry_type" required class="form-select">
                                <option value="general">General</option>
                                <option value="institutional_investment">Institutional Investment</option>
                                <option value="business_partnership">Business Partnership</option>
                                <option value="enterprise_it_services">Enterprise IT Services</option>
                                <option value="commodity_trade">Commodity Trade</option>
                                <option value="media_press">Media &amp; Press</option>
                                <option value="executive_careers">Executive Careers</option>
                            </select>
                        </div>
                        <div>
                            <label for="contact-name" class="form-label">Full name</label>
                            <input id="contact-name" type="text" name="full_name" required class="form-input">
                            @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="contact-email" class="form-label">Email</label>
                            <input id="contact-email" type="email" name="email" required class="form-input">
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="contact-phone" class="form-label">Phone</label>
                            <input id="contact-phone" type="text" name="phone" class="form-input">
                        </div>
                        <div>
                            <label for="contact-organization" class="form-label">Organization</label>
                            <input id="contact-organization" type="text" name="organization" class="form-input">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="contact-company" class="form-label">Which company is this regarding? (optional)</label>
                            <select id="contact-company" name="target_company_id" class="form-select">
                                <option value="">— Not specific —</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="contact-message" class="form-label">Message</label>
                            <textarea id="contact-message" name="message" rows="5" required class="form-textarea"></textarea>
                            @error('message')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="flex items-start gap-2 text-xs text-ink-muted">
                                <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded border-slate-300">
                                I consent to Ecozeen Group contacting me about this enquiry.
                            </label>
                        </div>
                        <div class="sm:col-span-2">
                            <button type="submit" class="btn-primary w-full">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
