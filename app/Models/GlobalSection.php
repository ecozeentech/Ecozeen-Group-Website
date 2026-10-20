<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalSection extends Model
{
    protected $fillable = ['name', 'identifier', 'type', 'settings'];

    protected function casts(): array
    {
        return ['settings' => 'array'];
    }

    public function pageSections()
    {
        return $this->hasMany(PageSection::class);
    }
}
