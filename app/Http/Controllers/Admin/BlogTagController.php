<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleTaxonomy;
use App\Http\Controllers\Controller;
use App\Models\BlogTag;

class BlogTagController extends Controller
{
    use ManagesSimpleTaxonomy;

    protected string $modelClass = BlogTag::class;

    protected string $permissionPrefix = 'blog';

    protected string $title = 'Blog Tags';

    protected string $routeName = 'blog-tags';

    protected bool $hasDescription = false;
}
