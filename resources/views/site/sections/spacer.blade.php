@php
    $heightClasses = ['sm' => 'h-8', 'md' => 'h-16', 'lg' => 'h-24', 'xl' => 'h-32'];
    $class = $heightClasses[$settings['height'] ?? 'md'] ?? 'h-16';
@endphp
<div class="{{ $class }}" aria-hidden="true"></div>
