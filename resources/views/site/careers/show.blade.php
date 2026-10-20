<x-layouts.public :seo-title="$listing->seo_title ?: $listing->title" :seo-description="$listing->seo_description">
    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $listing->company?->name }}</p>
                <h1 class="mt-2 font-heading text-3xl font-bold text-ink">{{ $listing->title }}</h1>
                <p class="mt-2 text-sm text-ink-muted">
                    {{ \Illuminate\Support\Str::headline($listing->employment_type) }} @if($listing->department) &middot; {{ $listing->department }} @endif @if($listing->location) &middot; {{ $listing->location }} @endif
                </p>

                <div class="prose prose-slate mt-8 max-w-none">{!! $listing->description !!}</div>

                @if ($listing->requirements)
                    <div class="mt-8">
                        <h2 class="font-heading text-xl font-bold text-ink">Requirements</h2>
                        <div class="prose prose-slate mt-3 max-w-none">{!! $listing->requirements !!}</div>
                    </div>
                @endif
            </div>

            <aside>
                @if ($listing->status === 'open')
                    <div class="card p-6" id="apply">
                        <h2 class="font-heading text-base font-semibold text-ink">Apply for this role</h2>

                        @if (session('status'))
                            <p class="mt-3 rounded-token border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">{{ session('status') }}</p>
                        @endif

                        <form method="POST" action="{{ route('careers.apply', $listing) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                            @csrf
                            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                            <div>
                                <label for="apply-name" class="form-label">Full name</label>
                                <input id="apply-name" type="text" name="full_name" required class="form-input">
                                @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="apply-email" class="form-label">Email</label>
                                <input id="apply-email" type="email" name="email" required class="form-input">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="apply-phone" class="form-label">Phone</label>
                                <input id="apply-phone" type="text" name="phone" class="form-input">
                            </div>
                            <div>
                                <label for="apply-cover" class="form-label">Cover letter</label>
                                <textarea id="apply-cover" name="cover_letter" rows="3" class="form-textarea"></textarea>
                            </div>
                            <div>
                                <label for="apply-resume" class="form-label">Resume (PDF, max 10&nbsp;MB)</label>
                                <input id="apply-resume" type="file" name="resume" accept="application/pdf" required class="form-input">
                                @error('resume')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <label class="flex items-start gap-2 text-xs text-ink-muted">
                                <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded border-slate-300">
                                I consent to Ecozeen Group processing my application data for recruitment purposes.
                            </label>
                            <button type="submit" class="btn-primary w-full">Submit Application</button>
                        </form>
                    </div>
                @else
                    <div class="card p-6 text-center">
                        <p class="text-sm font-medium text-ink-muted">This role is now closed.</p>
                    </div>
                @endif
            </aside>
        </div>
    </section>
</x-layouts.public>
