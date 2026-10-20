<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentRevision extends Model
{
    public $timestamps = false;

    protected $fillable = ['revisionable_type', 'revisionable_id', 'data', 'summary', 'created_by', 'created_at'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function revisionable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
