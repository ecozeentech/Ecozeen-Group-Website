<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleTaxonomy;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    use ManagesSimpleTaxonomy;

    protected string $modelClass = ProductCategory::class;

    protected string $permissionPrefix = 'products';

    protected string $title = 'Product Categories';

    protected string $routeName = 'product-categories';

    protected bool $hasDescription = true;
}
