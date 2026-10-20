@php
    $style = $settings['style'] ?? 'solid';
    $borderClass = $style === 'dashed' ? 'border-dashed' : ($style === 'dotted' ? 'border-dotted' : 'border-solid');
@endphp
<div class="container-site">
    <hr class="my-0 {{ $borderClass }} border-surface-border">
</div>
