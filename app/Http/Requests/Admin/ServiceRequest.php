<?php

namespace App\Http\Requests\Admin;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $service = $this->route('service') ?? new Service;

        return $this->isMethod('POST')
            ? $this->user()->can('create', Service::class)
            : $this->user()->can('update', $service);
    }

    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('services', 'slug')->ignore($serviceId)],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'cover_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'company_ids' => ['nullable', 'array'],
            'company_ids.*' => ['integer', 'exists:companies,id'],
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
