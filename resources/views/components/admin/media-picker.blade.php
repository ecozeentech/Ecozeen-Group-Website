@props(['label', 'field', 'current' => null, 'hint' => null])
@php
    $library = \App\Models\MediaAsset::query()->where('mime_type', 'like', 'image/%')->latest()->limit(100)->get();
@endphp
<div x-data="{ mode: '{{ $current ? 'existing' : 'upload' }}' }">
    <label class="form-label">{{ $label }}</label>

    @if ($current)
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ $current->url() }}" alt="{{ $current->alt_text }}" class="h-16 w-16 rounded-token border border-surface-border object-cover">
            <span class="text-xs text-ink-muted">Current: {{ $current->original_filename }}</span>
        </div>
    @endif

    <div class="flex gap-4 text-xs">
        <label class="inline-flex items-center gap-1.5">
            <input type="radio" x-model="mode" value="existing" class="text-brand-blue"> Choose from library
        </label>
        <label class="inline-flex items-center gap-1.5">
            <input type="radio" x-model="mode" value="upload" class="text-brand-blue"> Upload new
        </label>
        <label class="inline-flex items-center gap-1.5">
            <input type="radio" x-model="mode" value="none" class="text-brand-blue"> Remove
        </label>
    </div>

    <div class="mt-2" x-show="mode === 'existing'" x-cloak>
        <select name="{{ $field }}" class="form-select">
            <option value="">— None —</option>
            @foreach ($library as $asset)
                <option value="{{ $asset->id }}" @selected($current?->id === $asset->id)>{{ $asset->original_filename }} ({{ $asset->width }}&times;{{ $asset->height }})</option>
            @endforeach
        </select>
    </div>

    <div class="mt-2" x-show="mode === 'upload'" x-cloak>
        <input type="file" name="{{ $field }}_upload" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" class="form-input">
    </div>

    <div x-show="mode === 'none'" x-cloak>
        <input type="hidden" name="{{ $field }}" value="">
    </div>

    @if ($hint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
    @error($field)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
