<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(): View
    {
        $products = Product::query()
            ->with('company')
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%'))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return $this->formView(new Product);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        [$fields, $categoryIds] = $this->prepareData($request);

        $product = Product::create($fields);
        $product->categories()->sync($categoryIds);

        ActivityLogger::log('created', $product, "Created product {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return $this->formView($product);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        [$fields, $categoryIds] = $this->prepareData($request, $product);

        $product->update($fields);
        $product->categories()->sync($categoryIds);

        ActivityLogger::log('updated', $product, "Updated product {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        ActivityLogger::log('deleted', null, "Deleted product {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Product archived successfully.');
    }

    private function formView(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'companies' => Company::orderBy('name')->get(),
            'categories' => ProductCategory::orderBy('name')->get(),
        ]);
    }

    private function prepareData(ProductRequest $request, ?Product $product = null): array
    {
        $data = $request->validated();
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids'], $data['logo_media_id_upload'], $data['screenshot_media_id_upload'], $data['seo_image_media_id_upload']);

        if (array_key_exists('features_raw', $data)) {
            $data['features'] = array_values(array_filter(array_map(
                'trim',
                preg_split('/\r\n|\r|\n/', (string) $data['features_raw']) ?: []
            )));
        } elseif (array_key_exists('features', $data)) {
            $data['features'] = array_values(array_filter($data['features'] ?? []));
        } else {
            unset($data['features']);
        }
        unset($data['features_raw']);

        $data['logo_media_id'] = $this->resolveMediaId($request, 'logo_media_id', 'products', $product?->logo_media_id);
        $data['screenshot_media_id'] = $this->resolveMediaId($request, 'screenshot_media_id', 'products', $product?->screenshot_media_id);
        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo', $product?->seo_image_media_id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        return [$data, $categoryIds];
    }
}
