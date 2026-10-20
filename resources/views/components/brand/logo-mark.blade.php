@props(['class' => 'h-8 w-8'])
<img
    src="{{ asset('brand/ecozeen-mark.png') }}"
    alt=""
    {{ $attributes->merge(['class' => $class]) }}
    aria-hidden="true"
>
