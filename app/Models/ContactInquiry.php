<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = [
        'inquiry_type',
        'full_name',
        'email',
        'phone',
        'organization',
        'target_company_id',
        'jurisdiction',
        'message',
        'consent_at',
        'status',
        'handled_by',
        'internal_notes',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'consent_at' => 'datetime',
        ];
    }

    public function targetCompany()
    {
        return $this->belongsTo(Company::class, 'target_company_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
