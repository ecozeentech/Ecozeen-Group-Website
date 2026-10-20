@props(['label', 'name', 'type' => 'text', 'hint' => null, 'required' => false, 'value' => null])
<x-admin.field :label="$label" :name="$name" :hint="$hint" :required="$required">
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-input']) }}
    >
</x-admin.field>
