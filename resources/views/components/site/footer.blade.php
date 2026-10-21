@props(['settings'])
@php
    $footerMenu = \App\Services\NavigationService::menu('footer');
    $socialLinks = collect($settings->social_links ?? [])->filter();
    $operatingCompanies = \App\Models\Company::query()
        ->published()
        ->where('type', '!=', 'holding')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->limit(8)
        ->get();
    $copyright = '© '.now()->year.' '.($settings->site_name ?: 'Ecozeen Group').'. All rights reserved.';
    $legal = trim((string) $settings->footer_legal_text);
    $legalIsCopyright = $legal !== '' && (
        str_contains(mb_strtolower($legal), 'all rights reserved')
        || str_starts_with($legal, '©')
        || str_starts_with($legal, '(c)')
        || str_starts_with($legal, '(C)')
    );
    $copyrightLine = $legalIsCopyright ? $legal : $copyright;
    $extraLegal = ($legal !== '' && ! $legalIsCopyright) ? $legal : null;
    $footerColumns = $footerMenu?->items ?? collect();
@endphp
<footer class="border-t border-surface-border bg-surface" id="site-footer">
    <div class="container-site py-12 lg:py-16">
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12">
            <div class="sm:col-span-2 lg:col-span-4">
                <x-brand.logo :settings="$settings" />
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-muted">
                    {{ $settings->footer_text ?: $settings->supporting_statement ?: 'A diversified group building and supporting operating businesses across technology, finance, and the real economy.' }}
                </p>
                @if ($settings->contact_email || $settings->contact_phone)
                    <ul class="mt-5 space-y-1.5 text-sm text-ink">
                        @if ($settings->contact_email)
                            <li>
                                <a href="mailto:{{ $settings->contact_email }}" class="hover:text-brand-blue">{{ $settings->contact_email }}</a>
                            </li>
                        @endif
                        @if ($settings->contact_phone)
                            <li>{{ $settings->contact_phone }}</li>
                        @endif
                    </ul>
                @endif
                @if ($socialLinks->isNotEmpty())
                    <div class="mt-5 flex flex-wrap items-center gap-2">
                        @foreach ($socialLinks as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-surface-border px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-ink-muted transition hover:border-brand-blue hover:text-brand-blue" aria-label="{{ ucfirst($platform) }}">
                                {{ $platform }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @foreach ($footerColumns as $column)
                <div class="lg:col-span-2">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">{{ $column->label }}</p>
                    <ul class="mt-3 space-y-2">
                        @forelse ($column->children as $link)
                            <li>
                                <a href="{{ $link->resolvedUrl() }}" class="text-sm text-ink transition hover:text-brand-blue">{{ $link->label }}</a>
                            </li>
                        @empty
                            <li>
                                <a href="{{ $column->resolvedUrl() }}" class="text-sm text-ink transition hover:text-brand-blue">{{ $column->label }}</a>
                            </li>
                        @endforelse
                    </ul>
                </div>
            @endforeach

            @if ($operatingCompanies->isNotEmpty())
                <div class="{{ $footerColumns->isEmpty() ? 'lg:col-span-8' : 'lg:col-span-4' }}">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Operating companies</p>
                    <ul class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach ($operatingCompanies as $company)
                            <li>
                                <a href="{{ route('companies.show', $company) }}" class="text-sm text-ink transition hover:text-brand-blue">{{ $company->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if ($settings->newsletter_enabled)
            <div class="mt-12 rounded-2xl border border-surface-border bg-slate-50 p-6 sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-xl">
                        <p class="font-heading text-base font-semibold text-ink">Subscribe to Executive Dispatch</p>
                        <p class="mt-1 text-sm text-ink-muted">Occasional updates from across Ecozeen Group. Unsubscribe at any time.</p>
                    </div>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full max-w-md flex-col gap-2 sm:flex-row">
                        @csrf
                        <label for="footer-newsletter-email" class="sr-only">Email address</label>
                        <input id="footer-newsletter-email" type="email" name="email" required placeholder="name@organization.com" class="form-input flex-1">
                        <button type="submit" class="btn-secondary whitespace-nowrap">Subscribe</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="mt-10 border-t border-surface-border pt-6 text-xs text-ink-muted">
            <p>{{ $copyrightLine }}</p>
            @if ($extraLegal)
                <p class="mt-2">{{ $extraLegal }}</p>
            @endif
        </div>
    </div>
</footer>
