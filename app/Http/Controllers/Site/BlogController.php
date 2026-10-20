<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->published()
            ->with('category', 'author')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('name')->get(),
        ]);
    }

    public function category(BlogCategory $category): View
    {
        $posts = BlogPost::query()
            ->published()
            ->where('blog_category_id', $category->id)
            ->with('category', 'author')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('name')->get(),
            'activeCategory' => $category,
        ]);
    }

    public function tag(BlogTag $tag): View
    {
        $posts = BlogPost::query()
            ->published()
            ->whereHas('tags', fn ($q) => $q->where('blog_tags.id', $tag->id))
            ->with('category', 'author')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.blog.index', [
            'posts' => $posts,
            'categories' => BlogCategory::orderBy('name')->get(),
            'activeTag' => $tag,
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->isPubliclyVisible(), 404);

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->limit(3)->get();

        return view('site.blog.show', [
            'post' => $post->load('category', 'author', 'tags'),
            'related' => $related,
        ]);
    }
}
