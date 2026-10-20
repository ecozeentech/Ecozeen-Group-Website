<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProjectCategory extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'sort_order'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
