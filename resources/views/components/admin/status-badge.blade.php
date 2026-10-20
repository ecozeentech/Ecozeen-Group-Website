@props(['status'])
@php
    $map = [
        'published' => 'badge-success',
        'open' => 'badge-success',
        'subscribed' => 'badge-success',
        'resolved' => 'badge-success',
        'scheduled' => 'badge-info',
        'in_progress' => 'badge-info',
        'in_review' => 'badge-info',
        'new' => 'badge-info',
        'draft' => 'badge-neutral',
        'closed' => 'badge-neutral',
        'unsubscribed' => 'badge-neutral',
        'archived' => 'badge-danger',
        'spam' => 'badge-danger',
        'rejected' => 'badge-danger',
    ];
    $class = $map[$status] ?? 'badge-neutral';
@endphp
<span class="{{ $class }}">{{ \Illuminate\Support\Str::headline($status) }}</span>
