<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $project = $this->route('project') ?? new Project;

        return $this->isMethod('POST')
            ? $this->user()->can('create', Project::class)
            : $this->user()->can('update', $project);
    }

    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'project_category_id' => ['nullable', 'integer', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('projects', 'slug')->ignore($projectId)],
            'client' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'outcomes' => ['nullable', 'string', 'max:5000'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'cover_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'gallery_media_ids' => ['nullable', 'array'],
            'gallery_media_ids.*' => ['integer', 'exists:media_assets,id'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:services,id'],
            'completed_at' => ['nullable', 'date'],
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
