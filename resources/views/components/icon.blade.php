@props(['name', 'filled' => false])
<span
    {{ $attributes->merge(['class' => 'material-symbols-outlined']) }}
    @if ($filled) style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;" @endif
    aria-hidden="true"
>{{ $name }}</span>
