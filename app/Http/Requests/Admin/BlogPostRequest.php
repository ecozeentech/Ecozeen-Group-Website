<?php

namespace App\Http\Requests\Admin;

use App\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('blog_post') ?? new BlogPost;

        return $this->isMethod('POST')
            ? $this->user()->can('create', BlogPost::class)
            : $this->user()->can('update', $post);
    }

    public function rules(): array
    {
        $postId = $this->route('blog_post')?->id;

        return [
            'blog_category_id' => ['nullable', 'integer', 'exists:blog_categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($postId)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:50000'],
            'cover_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'cover_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['integer', 'exists:blog_tags,id'],
            'status' => ['required', Rule::in(['draft', 'in_review', 'scheduled', 'published', 'archived'])],
            'scheduled_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'seo_image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'seo_image_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'no_index' => ['nullable', 'boolean'],
        ];
    }
}
