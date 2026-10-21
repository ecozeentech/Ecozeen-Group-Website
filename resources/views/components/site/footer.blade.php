@props(['settings'])
@php
    $footerMenu = \App\Services\NavigationService::menu('footer');
    $socialLinks = $settings->social_links ?? [];
    $operatingCompanies = \App\Models\Company::query()
        ->published()
        ->where('type', '!=', 'holding')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->limit(8)
        ->get();
@endphp
<footer class="border-t border-surface-border bg-white">
    <div class="container-site py-12 lg:py-16">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-brand.logo :settings="$settings" />
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-ink-muted">
                    {{ $settings->footer_text ?: $settings->supporting_statement ?: 'A diversified group building and supporting operating businesses across technology, finance, and the real economy.' }}
                </p>
                @if (! empty($socialLinks))
                    <div class="mt-5 flex items-center gap-3">
                        @foreach ($socialLinks as $platform => $url)
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold uppercase text-ink-muted hover:text-brand-blue" aria-label="{{ ucfirst($platform) }}">
                                    {{ $platform }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($footerMenu && $footerMenu->items->isNotEmpty())
                @foreach ($footerMenu->items as $column)
                    <div class="lg:col-span-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">{{ $column->label }}</p>
                        <ul class="mt-3 space-y-2">
                            @foreach ($column->children as $link)
                                <li>
                                    <a href="{{ $link->resolvedUrl() }}" class="text-sm text-ink hover:text-brand-blue">{{ $link->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endif

            @if ($operatingCompanies->isNotEmpty())
                <div class="lg:col-span-4">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Operating companies</p>
                    <ul class="mt-3 columns-1 gap-x-6 space-y-2 sm:columns-2">
                        @foreach ($operatingCompanies as $company)
                            <li class="break-inside-avoid">
                                <a href="{{ route('companies.show', $company) }}" class="text-sm text-ink hover:text-brand-blue">{{ $company->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if ($settings->newsletter_enabled)
            <div class="mt-10 flex flex-col gap-4 rounded-2xl border border-surface-border bg-slate-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-heading text-sm font-semibold text-ink">Subscribe to Executive Dispatch</p>
                    <p class="text-xs text-ink-muted">Occasional updates from across Ecozeen Group.</p>
                </div>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full max-w-sm gap-2 sm:w-auto">
                    @csrf
                    <label for="footer-newsletter-email" class="sr-only">Email address</label>
                    <input id="footer-newsletter-email" type="email" name="email" required placeholder="name@organization.com" class="form-input flex-1">
                    <button type="submit" class="btn-secondary whitespace-nowrap">Subscribe</button>
                </form>
            </div>
        @endif

        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-surface-border pt-6 text-xs text-ink-muted sm:flex-row">
            <p>&copy; {{ now()->year }} {{ $settings->site_name ?: 'Ecozeen Group' }}. All rights reserved.</p>
            <p>{{ $settings->footer_legal_text }}</p>
        </div>
    </div>
</footer>
