<?php

namespace App\Http\Requests\Admin;

use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('page') ?? new Page;

        return $this->isMethod('POST')
            ? $this->user()->can('create', Page::class)
            : $this->user()->can('update', $page);
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('pages', 'slug')->ignore($pageId)],
            'template' => ['required', Rule::in(['default', 'landing', 'full-width'])],
            'status' => ['required', Rule::in(['draft', 'in_review', 'scheduled', 'published', 'archived'])],
            'scheduled_at' => ['nullable', 'date'],
            'is_homepage' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'seo_image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'seo_image_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'no_index' => ['nullable', 'boolean'],
        ];
    }
}
