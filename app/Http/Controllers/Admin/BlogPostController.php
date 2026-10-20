<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(BlogPost::class, 'blog_post');
    }

    public function index(): View
    {
        $posts = BlogPost::query()
            ->with('category', 'author')
            ->when(request('q'), fn ($q) => $q->where('title', 'like', '%'.request('q').'%'))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create(): View
    {
        return $this->formView(new BlogPost);
    }

    public function store(BlogPostRequest $request): RedirectResponse
    {
        [$data, $tagIds] = $this->prepareData($request);
        $data['author_id'] = $data['author_id'] ?? Auth::id();

        $post = BlogPost::create($data);
        $post->tags()->sync($tagIds);

        ActivityLogger::log('created', $post, "Created blog post \"{$post->title}\"");

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return $this->formView($blogPost);
    }

    public function update(BlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        [$data, $tagIds] = $this->prepareData($request, $blogPost);

        $blogPost->update($data);
        $blogPost->tags()->sync($tagIds);

        ActivityLogger::log('updated', $blogPost, "Updated blog post \"{$blogPost->title}\"");

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();
        ActivityLogger::log('deleted', null, "Deleted blog post \"{$blogPost->title}\"");

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post archived successfully.');
    }

    public function publish(BlogPost $blogPost): RedirectResponse
    {
        $this->authorize('publish', $blogPost);

        $blogPost->update(['status' => 'published', 'published_at' => $blogPost->published_at ?: now()]);
        ActivityLogger::log('published', $blogPost, "Published blog post \"{$blogPost->title}\"");

        return back()->with('success', 'Post published.');
    }

    private function formView(BlogPost $post): View
    {
        return view('admin.blog-posts.form', [
            'post' => $post,
            'categories' => BlogCategory::orderBy('name')->get(),
            'tags' => BlogTag::orderBy('name')->get(),
        ]);
    }

    private function prepareData(BlogPostRequest $request, ?BlogPost $post = null): array
    {
        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids'], $data['cover_media_id_upload'], $data['seo_image_media_id_upload']);

        $data['cover_media_id'] = $this->resolveMediaId($request, 'cover_media_id', 'blog', $post?->cover_media_id);
        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo', $post?->seo_image_media_id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        if (empty($data['slug'])) {
            unset($data['slug']);
        }

        return [$data, $tagIds];
    }
}
