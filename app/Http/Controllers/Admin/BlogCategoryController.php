<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleTaxonomy;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    use ManagesSimpleTaxonomy;

    protected string $modelClass = BlogCategory::class;

    protected string $permissionPrefix = 'blog';

    protected string $title = 'Blog Categories';

    protected string $routeName = 'blog-categories';

    protected bool $hasDescription = true;
}
