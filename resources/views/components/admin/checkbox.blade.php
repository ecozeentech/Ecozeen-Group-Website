@props(['label', 'name', 'checked' => false, 'hint' => null])
<div class="flex items-start gap-2">
    <input
        type="checkbox"
        id="{{ $name }}"
        name="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        {{ $attributes->merge(['class' => 'mt-0.5 rounded border-slate-300 text-brand-blue focus:ring-brand-blue/30']) }}
    >
    <label for="{{ $name }}" class="text-sm text-ink">
        {{ $label }}
        @if ($hint)
            <span class="block text-xs text-ink-muted">{{ $hint }}</span>
        @endif
    </label>
</div>
