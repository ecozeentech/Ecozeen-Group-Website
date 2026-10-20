<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'status',
        'unsubscribe_token',
        'consent_at',
        'subscribed_at',
        'unsubscribed_at',
        'source',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'consent_at' => 'datetime',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }
}
