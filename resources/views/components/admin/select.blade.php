@props(['label', 'name', 'options' => [], 'hint' => null, 'required' => false, 'value' => null, 'placeholder' => null])
<x-admin.field :label="$label" :name="$name" :hint="$hint" :required="$required">
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-select']) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</x-admin.field>
