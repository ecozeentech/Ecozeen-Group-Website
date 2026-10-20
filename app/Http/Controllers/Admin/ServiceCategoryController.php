<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleTaxonomy;
use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    use ManagesSimpleTaxonomy;

    protected string $modelClass = ServiceCategory::class;

    protected string $permissionPrefix = 'services';

    protected string $title = 'Service Categories';

    protected string $routeName = 'service-categories';

    protected bool $hasDescription = true;
}
