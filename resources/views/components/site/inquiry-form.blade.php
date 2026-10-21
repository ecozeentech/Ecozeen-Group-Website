@props([
    'companies',
    'heading' => 'Official Communications Router',
    'submitLabel' => 'Dispatch Secure Inquiry',
    'defaultType' => 'general',
])
<form method="POST" action="{{ route('contact.store') }}" class="form-public space-y-5">
    @csrf
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

    <h3 class="font-heading text-lg font-semibold text-ink">{{ $heading }}</h3>

    <div>
        <p class="mb-2 text-[11px] font-bold uppercase tracking-wide text-ink-muted">Primary Intent / Routing Sector</p>
        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">
            @foreach ([
                'general' => 'General Executive',
                'business_partnership' => 'Business Partnership',
                'enterprise_it_services' => 'Enterprise Services',
                'institutional_investment' => 'Investor Relations',
                'media_press' => 'Media & Press',
                'executive_careers' => 'Careers & Talent',
                'commodity_trade' => 'Commodity Trade',
            ] as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="inquiry_type" value="{{ $value }}" class="peer sr-only" @checked(old('inquiry_type', $defaultType) === $value) required>
                    <span class="block rounded-lg border border-surface-border bg-slate-50 px-3 py-3 text-center text-[12px] font-medium text-ink transition hover:border-brand-blue peer-checked:border-brand-blue peer-checked:bg-brand-blue peer-checked:text-white">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="inquiry-name" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Full Legal Name *</label>
            <input id="inquiry-name" type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="e.g. Jonathan Sterling" class="form-input h-11">
            @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="inquiry-email" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Institutional Email *</label>
            <input id="inquiry-email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@organization.com" class="form-input h-11">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="inquiry-phone" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Phone</label>
            <input id="inquiry-phone" type="text" name="phone" value="{{ old('phone') }}" class="form-input h-11">
        </div>
        <div>
            <label for="inquiry-organization" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Organization / Entity Name</label>
            <input id="inquiry-organization" type="text" name="organization" value="{{ old('organization') }}" class="form-input h-11">
        </div>
        <div class="sm:col-span-2">
            <label for="inquiry-company" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Relevant Subsidiary Target</label>
            <select id="inquiry-company" name="target_company_id" class="form-select h-11">
                <option value="">Ecozeen Group (Whole Holding)</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @selected(old('target_company_id') == $company->id)>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="inquiry-message" class="form-label text-[11px] font-bold uppercase tracking-wide text-ink-muted">Detailed Briefing / Proposal Scope *</label>
            <textarea id="inquiry-message" name="message" rows="4" required placeholder="Outline the strategic parameters, timeline, and objective of this inquiry..." class="form-textarea">{{ old('message') }}</textarea>
            @error('message')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <label class="flex items-start gap-2.5 text-[11px] leading-tight text-ink-muted">
        <input type="checkbox" name="consent" value="1" required class="mt-1 rounded border-slate-300 text-brand-blue focus:ring-brand-blue" @checked(old('consent'))>
        I agree to the processing of corporate contact details in accordance with Ecozeen Group Compliance &amp; Governance Charter and applicable UK GDPR &amp; NDPR protocols.
    </label>
    @error('consent')<p class="form-error">{{ $message }}</p>@enderror

    <button type="submit" class="btn-primary h-12 w-full rounded-xl">
        <span>{{ $submitLabel }}</span>
        <x-icon name="send" class="text-[18px]" />
    </button>
</form>
