<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->published()
            ->with('company', 'categories', 'logoMedia', 'screenshotMedia')
            ->when(request('category'), fn ($q) => $q->whereHas('categories', fn ($c) => $c->where('slug', request('category'))))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = ProductCategory::orderBy('name')->get();

        return view('site.products.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'published', 404);

        return view('site.products.show', ['product' => $product->load('company', 'categories')]);
    }
}
