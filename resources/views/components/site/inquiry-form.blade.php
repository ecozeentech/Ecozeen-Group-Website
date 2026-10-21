@props([
    'companies',
    'heading' => 'Official Communications Router',
    'submitLabel' => 'Dispatch Secure Inquiry',
    'defaultType' => 'general',
])
<form method="POST" action="{{ route('contact.store') }}" class="form-public grid grid-cols-1 gap-4 sm:grid-cols-2">
    @csrf
    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

    <div class="sm:col-span-2">
        <p class="form-label">{{ $heading }}</p>
        <div class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-3">
            @foreach ([
                'general' => 'General',
                'institutional_investment' => 'Institutional Investment',
                'business_partnership' => 'Business Partnership',
                'enterprise_it_services' => 'Enterprise IT Services',
                'commodity_trade' => 'Commodity Trade',
                'media_press' => 'Media & Press',
                'executive_careers' => 'Executive Careers',
            ] as $value => $label)
                <label class="cursor-pointer">
                    <input type="radio" name="inquiry_type" value="{{ $value }}" class="peer sr-only" @checked(old('inquiry_type', $defaultType) === $value) required>
                    <span class="block rounded-lg border border-surface-border px-3 py-2 text-center text-sm text-ink-muted transition peer-checked:border-brand-blue peer-checked:bg-brand-blue peer-checked:text-white">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <label for="inquiry-name" class="form-label">Full name</label>
        <input id="inquiry-name" type="text" name="full_name" value="{{ old('full_name') }}" required class="form-input">
        @error('full_name')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="inquiry-email" class="form-label">Email</label>
        <input id="inquiry-email" type="email" name="email" value="{{ old('email') }}" required class="form-input">
        @error('email')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="inquiry-phone" class="form-label">Phone</label>
        <input id="inquiry-phone" type="text" name="phone" value="{{ old('phone') }}" class="form-input">
    </div>
    <div>
        <label for="inquiry-organization" class="form-label">Organization</label>
        <input id="inquiry-organization" type="text" name="organization" value="{{ old('organization') }}" class="form-input">
    </div>
    <div class="sm:col-span-2">
        <label for="inquiry-company" class="form-label">Which company is this regarding? (optional)</label>
        <select id="inquiry-company" name="target_company_id" class="form-select">
            <option value="">— Not specific —</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" @selected(old('target_company_id') == $company->id)>{{ $company->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="inquiry-message" class="form-label">Message</label>
        <textarea id="inquiry-message" name="message" rows="5" required class="form-textarea">{{ old('message') }}</textarea>
        @error('message')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="flex items-start gap-2 text-xs text-ink-muted">
            <input type="checkbox" name="consent" value="1" required class="mt-0.5 rounded border-slate-300" @checked(old('consent'))>
            I consent to Ecozeen Group contacting me about this enquiry.
        </label>
        @error('consent')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <button type="submit" class="btn-primary w-full">
            {{ $submitLabel }}
            <x-icon name="send" class="text-[18px]" />
        </button>
    </div>
</form>
