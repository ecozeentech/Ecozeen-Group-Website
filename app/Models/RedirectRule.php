<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedirectRule extends Model
{
    protected $table = 'redirects';

    protected $fillable = ['from_path', 'to_path', 'status_code', 'is_active', 'hits'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
