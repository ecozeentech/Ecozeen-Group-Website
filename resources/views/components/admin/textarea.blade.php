@props(['label', 'name', 'hint' => null, 'required' => false, 'value' => null, 'rows' => 4])
<x-admin.field :label="$label" :name="$name" :hint="$hint" :required="$required">
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'form-textarea']) }}
    >{{ old($name, $value) }}</textarea>
</x-admin.field>
