<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product') ?? new Product;

        return $this->isMethod('POST')
            ? $this->user()->can('create', Product::class)
            : $this->user()->can('update', $product);
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'logo_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'logo_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'screenshot_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'screenshot_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:product_categories,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'seo_image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'seo_image_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'no_index' => ['nullable', 'boolean'],
        ];
    }
}
