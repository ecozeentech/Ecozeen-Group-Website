@php($editing = $post->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Post'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Post'" />

    <form method="POST" action="{{ $editing ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Post Content</h2>
                <x-admin.input label="Title" name="title" :value="$post->title" required />
                <x-admin.input label="Slug" name="slug" :value="$post->slug" hint="Leave blank to auto-generate." />
                <x-admin.textarea label="Excerpt" name="excerpt" :value="$post->excerpt" hint="Shown on listing cards (max 500 characters)." />
                <x-admin.textarea label="Body" name="body" :value="$post->body" rows="14" hint="Basic HTML formatting is sanitized and preserved." />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Tags</h2>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($tags as $tag)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" @checked($post->tags->contains($tag->id)) class="rounded border-slate-300 text-brand-blue">
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">SEO</h2>
                <x-admin.input label="SEO title" name="seo_title" :value="$post->seo_title" />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$post->seo_description" rows="2" />
                <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$post->seoImageMedia" />
                <x-admin.checkbox label="No-index this post" name="no_index" :checked="$post->no_index" />
            </div>
        </div>

        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Publishing</h2>
                <x-admin.select label="Category" name="blog_category_id" :value="$post->blog_category_id" placeholder="— None —" :options="$categories->pluck('name', 'id')" />
                <x-admin.select label="Status" name="status" :value="$post->status ?? 'draft'" :options="['draft' => 'Draft', 'in_review' => 'In Review', 'scheduled' => 'Scheduled', 'published' => 'Published', 'archived' => 'Archived']" required />
                <x-admin.input type="datetime-local" label="Scheduled publish time" name="scheduled_at" :value="$post->scheduled_at?->format('Y-m-d\TH:i')" />
                <x-admin.checkbox label="Feature on homepage" name="is_featured" :checked="$post->is_featured" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Cover Image</h2>
                <x-admin.media-picker label="Cover image" field="cover_media_id" :current="$post->coverMedia" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn-primary w-full">{{ $editing ? 'Save changes' : 'Create post' }}</button>
                <a href="{{ route('admin.blog-posts.index') }}" class="btn-outline w-full text-center">Cancel</a>
            </div>
        </div>
    </form>
</x-layouts.admin>
