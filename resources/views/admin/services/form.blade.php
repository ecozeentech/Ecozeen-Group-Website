@php($editing = $service->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Service'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Service'" />

    <form method="POST" action="{{ $editing ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Service Details</h2>
                <x-admin.input label="Name" name="name" :value="$service->name" required />
                <x-admin.select label="Category" name="service_category_id" :value="$service->service_category_id" placeholder="— None —" :options="$categories->pluck('name', 'id')" />
                <x-admin.input label="Icon identifier" name="icon" :value="$service->icon" hint="Optional identifier used by the icon component (e.g. 'shield', 'cube')." />
                <x-admin.textarea label="Summary" name="summary" :value="$service->summary" />
                <x-admin.textarea label="Full description" name="description" :value="$service->description" rows="8" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Delivered by</h2>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($companies as $company)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="company_ids[]" value="{{ $company->id }}" @checked($service->companies->contains($company->id)) class="rounded border-slate-300 text-brand-blue">
                            {{ $company->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">SEO</h2>
                <x-admin.input label="SEO title" name="seo_title" :value="$service->seo_title" />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$service->seo_description" rows="2" />
                <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$service->seoImageMedia" />
                <x-admin.checkbox label="No-index this page" name="no_index" :checked="$service->no_index" />
            </div>
        </div>

        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Publishing</h2>
                <x-admin.select label="Status" name="status" :value="$service->status ?? 'draft'" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" required />
                <x-admin.checkbox label="Feature on homepage" name="is_featured" :checked="$service->is_featured" />
                <x-admin.input type="number" label="Sort order" name="sort_order" :value="$service->sort_order ?? 0" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Cover Image</h2>
                <x-admin.media-picker label="Cover image" field="cover_media_id" :current="$service->coverMedia" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn-primary w-full">{{ $editing ? 'Save changes' : 'Create service' }}</button>
                <a href="{{ route('admin.services.index') }}" class="btn-outline w-full text-center">Cancel</a>
            </div>
        </div>
    </form>
</x-layouts.admin>
