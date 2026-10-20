<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'global_section_id',
        'type',
        'settings',
        'position',
        'is_visible',
        'visible_desktop',
        'visible_mobile',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_visible' => 'boolean',
            'visible_desktop' => 'boolean',
            'visible_mobile' => 'boolean',
        ];
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function globalSection()
    {
        return $this->belongsTo(GlobalSection::class);
    }

    /**
     * Effective settings: inherited from the global section when this is a
     * reference to a reusable section, otherwise the section's own settings.
     */
    public function effectiveSettings(): array
    {
        if ($this->globalSection) {
            return $this->globalSection->settings ?? [];
        }

        return $this->settings ?? [];
    }
}
