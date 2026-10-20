@php($editing = $project->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Project'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Project'" />

    <form method="POST" action="{{ $editing ? route('admin.projects.update', $project) : route('admin.projects.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Project Details</h2>
                <x-admin.input label="Title" name="title" :value="$project->title" required />
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.select label="Company" name="company_id" :value="$project->company_id" placeholder="— None —" :options="$companies->pluck('name', 'id')" />
                    <x-admin.select label="Category" name="project_category_id" :value="$project->project_category_id" placeholder="— None —" :options="$categories->pluck('name', 'id')" />
                    <x-admin.input label="Client" name="client" :value="$project->client" hint="Only verified, public information." />
                    <x-admin.input type="date" label="Completed on" name="completed_at" :value="$project->completed_at?->format('Y-m-d')" />
                </div>
                <x-admin.textarea label="Summary" name="summary" :value="$project->summary" />
                <x-admin.textarea label="Full description" name="description" :value="$project->description" rows="6" />
                <x-admin.textarea label="Verified outcomes" name="outcomes" :value="$project->outcomes" rows="4" hint="Only include outcomes that are verified and supportable." />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Gallery</h2>
                <p class="text-xs text-ink-muted">Select images from the media library, in display order.</p>
                <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                    @foreach ($mediaLibrary as $asset)
                        <label class="relative block">
                            <input type="checkbox" name="gallery_media_ids[]" value="{{ $asset->id }}" @checked($project->galleryMedia->contains($asset->id)) class="absolute right-1 top-1 rounded border-slate-300">
                            <img src="{{ $asset->url() }}" class="h-20 w-full rounded-token border border-surface-border object-cover" alt="{{ $asset->alt_text }}">
                        </label>
                    @endforeach
                </div>
                @if ($mediaLibrary->isEmpty())
                    <p class="text-sm text-ink-muted">No images uploaded yet. Visit the Media Library to upload gallery images.</p>
                @endif
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Related Services</h2>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($services as $service)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" @checked($project->services->contains($service->id)) class="rounded border-slate-300 text-brand-blue">
                            {{ $service->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">SEO</h2>
                <x-admin.input label="SEO title" name="seo_title" :value="$project->seo_title" />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$project->seo_description" rows="2" />
                <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$project->seoImageMedia" />
                <x-admin.checkbox label="No-index this page" name="no_index" :checked="$project->no_index" />
            </div>
        </div>

        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Publishing</h2>
                <x-admin.select label="Status" name="status" :value="$project->status ?? 'draft'" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" required />
                <x-admin.checkbox label="Feature on homepage" name="is_featured" :checked="$project->is_featured" />
                <x-admin.input type="number" label="Sort order" name="sort_order" :value="$project->sort_order ?? 0" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Cover Image</h2>
                <x-admin.media-picker label="Cover image" field="cover_media_id" :current="$project->coverMedia" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn-primary w-full">{{ $editing ? 'Save changes' : 'Create project' }}</button>
                <a href="{{ route('admin.projects.index') }}" class="btn-outline w-full text-center">Cancel</a>
            </div>
        </div>
    </form>
</x-layouts.admin>
