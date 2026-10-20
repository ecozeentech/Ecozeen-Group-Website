@props(['label', 'name', 'hint' => null, 'required' => false])
<div>
    <label for="{{ $name }}" class="form-label">
        {{ $label }} @if ($required)<span class="text-red-500">*</span>@endif
    </label>
    {{ $slot }}
    @if ($hint)
        <p class="form-hint">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
