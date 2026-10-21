@php
    $settings = \App\Models\WebsiteSetting::current();
@endphp
<x-layouts.public seo-title="Contact" seo-description="Get in touch with Ecozeen Group.">
    <section class="container-site pt-14 pb-10">
        <div class="max-w-4xl border-l-4 border-brand-blue pl-6">
            <span class="badge-info mb-3 uppercase tracking-wider">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-brand-blue"></span>
                Direct engagement &amp; group inquiries
            </span>
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-ink md:text-5xl">Initiate an institutional dialogue</h1>
            <p class="mt-3 max-w-3xl text-lg text-ink-muted">Connect with Ecozeen Group on investment, partnership, enterprise services, or press. We route every verified enquiry to the relevant team.</p>
        </div>
    </section>

    <section class="container-site pb-20">
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
            <div class="flex flex-col gap-6 lg:col-span-5">
                <div class="card p-6">
                    <h2 class="mb-5 flex items-center gap-2 font-heading text-lg font-semibold text-ink">
                        <x-icon name="domain" class="text-brand-blue" />
                        Group communications
                    </h2>
                    @if ($settings->address_primary || $settings->address_secondary)
                        @if ($settings->address_primary)
                            <div class="pb-5 {{ $settings->address_secondary ? 'border-b border-surface-border' : '' }}">
                                <span class="text-[11px] font-bold uppercase text-brand-blue">Primary office</span>
                                <p class="mt-2 flex items-start gap-2 text-sm text-ink">
                                    <x-icon name="location_on" class="text-ink-muted text-[18px]" />
                                    {{ $settings->address_primary }}
                                </p>
                            </div>
                        @endif
                        @if ($settings->address_secondary)
                            <div class="{{ $settings->address_primary ? 'pt-5' : '' }}">
                                <span class="text-[11px] font-bold uppercase text-brand-blue">Additional location</span>
                                <p class="mt-2 flex items-start gap-2 text-sm text-ink">
                                    <x-icon name="location_on" class="text-ink-muted text-[18px]" />
                                    {{ $settings->address_secondary }}
                                </p>
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-ink-muted">Office addresses can be published by an administrator in Website Settings.</p>
                    @endif
                </div>

                <div class="card p-6">
                    <h2 class="mb-4 flex items-center gap-2 font-heading text-lg font-semibold text-ink">
                        <x-icon name="mail" class="text-brand-blue" />
                        Institutional communications
                    </h2>
                    <div class="space-y-4">
                        @if ($settings->contact_email)
                            <div class="flex items-start gap-3 rounded-lg border border-surface-border bg-slate-50 p-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-md bg-blue-50 text-brand-blue">
                                    <x-icon name="corporate_fare" class="text-[20px]" />
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase text-ink-muted">Official holding inquiries</p>
                                    <a href="mailto:{{ $settings->contact_email }}" class="text-sm font-semibold text-brand-blue hover:underline">{{ $settings->contact_email }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($settings->press_email)
                            <div class="flex items-start gap-3 rounded-lg border border-surface-border bg-slate-50 p-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-md bg-blue-50 text-brand-blue">
                                    <x-icon name="campaign" class="text-[20px]" />
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase text-ink-muted">Media &amp; press relations</p>
                                    <a href="mailto:{{ $settings->press_email }}" class="text-sm font-semibold text-brand-blue hover:underline">{{ $settings->press_email }}</a>
                                </div>
                            </div>
                        @endif
                        @if ($settings->contact_phone)
                            <div class="flex items-start gap-3 rounded-lg border border-surface-border bg-slate-50 p-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-md bg-blue-50 text-brand-blue">
                                    <x-icon name="call" class="text-[20px]" />
                                </span>
                                <div>
                                    <p class="text-[11px] uppercase text-ink-muted">Phone</p>
                                    <p class="text-sm font-semibold text-ink">{{ $settings->contact_phone }}</p>
                                </div>
                            </div>
                        @endif
                        @if (! $settings->contact_email && ! $settings->press_email && ! $settings->contact_phone)
                            <p class="text-sm text-ink-muted">Use the form to send a message. Email and phone can be added in Website Settings.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-r-xl border-l-4 border-brand-blue bg-blue-50 p-5">
                    <div class="flex items-start gap-3">
                        <x-icon name="info" class="mt-0.5 text-brand-blue text-[22px]" />
                        <div>
                            <h4 class="text-sm font-bold text-ink">Subsidiary product support</h4>
                            <p class="mt-1 text-sm text-ink-muted">For individual customer accounts and in-product assistance, use the support channels published by the relevant operating company rather than this group form.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-surface-border bg-white p-4">
                    <x-icon name="verified_user" class="rounded-lg bg-emerald-50 p-2 text-emerald-700 text-[22px]" />
                    <p class="text-sm text-ink-muted">Institutional conversations are treated as confidential. Do not send passwords or payment credentials through this form.</p>
                </div>
            </div>

            <div id="inquiry-form" class="card p-8 lg:col-span-7">
                <div class="mb-6 flex items-center justify-between border-b border-surface-border pb-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">Secure transmission</p>
                        <h2 class="font-heading text-2xl font-semibold text-ink">Intelligent communications router</h2>
                    </div>
                    <span class="hidden items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-[11px] text-ink sm:inline-flex">
                        <span class="h-2 w-2 rounded-full bg-brand-emerald"></span>
                        Encrypted form
                    </span>
                </div>
                @if (session('status'))
                    <p class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
                @endif
                <x-site.inquiry-form :companies="$companies" heading="Primary inquiry type" />
            </div>
        </div>
    </section>

    <section class="container-site pb-24">
        <div class="card p-8 lg:p-12" x-data="{ open: null }">
            <div class="mb-8 max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">Service benchmarks</p>
                <h2 class="mt-2 font-heading text-3xl font-bold text-ink">FAQ &amp; response practice</h2>
                <p class="mt-2 text-ink-muted">How Ecozeen Group reviews inquiries. These are operating practices, not contractual service levels.</p>
            </div>
            <div class="divide-y divide-surface-border">
                @foreach ([
                    ['q' => 'What is the turnaround time for corporate inquiries?', 'a' => 'We review every verified enquiry and route it to the relevant team. Response timing depends on the inquiry type and whether additional diligence is required.'],
                    ['q' => 'How are investment and partnership proposals evaluated?', 'a' => 'Collaborative engagements are screened for fit, governance, and the operating company that should own the relationship. If we proceed, a liaison will follow up with next steps.'],
                    ['q' => 'Can I contact a subsidiary directly?', 'a' => 'Yes. Choose the company in the form, or use a published subsidiary website when you need product support rather than a group-level conversation.'],
                    ['q' => 'Do you publish a physical office directory?', 'a' => 'Addresses and phone numbers appear on this page only when an administrator has entered them in Website Settings. We do not invent contact details for the design.'],
                ] as $i => $faq)
                    <div class="py-4">
                        <button type="button" class="flex w-full items-center justify-between gap-4 text-left" @click="open = open === {{ $i }} ? null : {{ $i }}" :aria-expanded="open === {{ $i }}">
                            <span class="font-heading text-lg text-ink">{{ $faq['q'] }}</span>
                            <x-icon name="expand_more" class="text-ink-muted" />
                        </button>
                        <p class="mt-2 text-ink-muted" x-show="open === {{ $i }}" x-cloak>{{ $faq['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
