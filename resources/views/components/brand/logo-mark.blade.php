@props(['class' => 'h-8 w-8'])
{{-- Placeholder shield-and-E brand mark. Replace via Admin > Settings > Website Settings
     by uploading the approved Ecozeen Group logo to the media library. --}}
<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    <path d="M24 2 44 10V22C44 34 35 43 24 46 13 43 4 34 4 22V10L24 2Z" fill="currentColor"/>
    <path d="M18 16H30V21H23V21.5C23 23 24 24 26 24H30V29H25C21 29 18 26.5 18 22.5V16Z" fill="white"/>
    <path d="M18 29V24H23V29H18Z" fill="white"/>
</svg>
