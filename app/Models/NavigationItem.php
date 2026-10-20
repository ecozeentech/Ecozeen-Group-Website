<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'navigation_menu_id',
        'parent_id',
        'page_id',
        'label',
        'url',
        'open_in_new_tab',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function menu()
    {
        return $this->belongsTo(NavigationMenu::class, 'navigation_menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function resolvedUrl(): string
    {
        if ($this->page) {
            return $this->page->is_homepage ? '/' : '/'.$this->page->slug;
        }

        return $this->url ?: '#';
    }
}
