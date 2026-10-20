<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleTaxonomy;
use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;

class ProjectCategoryController extends Controller
{
    use ManagesSimpleTaxonomy;

    protected string $modelClass = ProjectCategory::class;

    protected string $permissionPrefix = 'projects';

    protected string $title = 'Portfolio Categories';

    protected string $routeName = 'project-categories';

    protected bool $hasDescription = true;
}
