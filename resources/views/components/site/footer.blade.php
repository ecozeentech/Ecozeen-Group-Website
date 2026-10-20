@props(['settings'])
@php
    $footerMenu = \App\Services\NavigationService::menu('footer');
    $socialLinks = $settings->social_links ?? [];
@endphp
<footer class="border-t border-surface-border bg-surface-canvas">
    <div class="container-site py-12 lg:py-16">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <x-brand.logo :settings="$settings" />
                <p class="mt-4 max-w-sm text-sm text-ink-muted">
                    {{ $settings->footer_text ?: $settings->supporting_statement }}
                </p>
                @if (! empty($socialLinks))
                    <div class="mt-5 flex items-center gap-3">
                        @foreach ($socialLinks as $platform => $url)
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-ink-muted hover:text-brand-blue" aria-label="{{ ucfirst($platform) }}">
                                    <span class="text-xs font-semibold uppercase">{{ substr($platform, 0, 2) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($footerMenu)
                @foreach ($footerMenu->items as $column)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">{{ $column->label }}</p>
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
        </div>

        @if ($settings->newsletter_enabled)
            <div class="mt-10 flex flex-col gap-4 rounded-xl border border-surface-border bg-white p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-heading text-sm font-semibold text-ink">Subscribe to Executive Dispatch</p>
                    <p class="text-xs text-ink-muted">Curated corporate and market intelligence, delivered quarterly.</p>
                </div>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full max-w-sm gap-2 sm:w-auto">
                    @csrf
                    <label for="footer-newsletter-email" class="sr-only">Email address</label>
                    <input id="footer-newsletter-email" type="email" name="email" required placeholder="name@institution.com" class="form-input flex-1">
                    <button type="submit" class="btn-secondary whitespace-nowrap">Subscribe</button>
                </form>
            </div>
        @endif

        <div class="mt-10 flex flex-col items-center justify-between gap-3 border-t border-surface-border pt-6 text-xs text-ink-muted sm:flex-row">
            <p>&copy; {{ now()->year }} {{ $settings->site_name ?: 'Ecozeen Group' }}. All rights reserved.</p>
            <p>{{ $settings->footer_legal_text ?: 'Verified Institutional Entity & Ecological Digital Holdings.' }}</p>
        </div>
    </div>
</footer>
