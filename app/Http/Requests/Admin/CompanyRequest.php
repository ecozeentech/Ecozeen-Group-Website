<?php

namespace App\Http\Requests\Admin;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $company = $this->route('company') ?? new Company;

        return $this->isMethod('POST')
            ? $this->user()->can('create', Company::class)
            : $this->user()->can('update', $company);
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('companies', 'slug')->ignore($companyId)],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['holding', 'subsidiary', 'division', 'joint_venture'])],
            'parent_company_id' => ['nullable', 'integer', Rule::exists('companies', 'id')->whereNot('id', $companyId)],
            'category' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'logo_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'logo_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'cover_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:'.(now()->year + 1)],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'seo_image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'seo_image_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'no_index' => ['nullable', 'boolean'],
        ];
    }
}
