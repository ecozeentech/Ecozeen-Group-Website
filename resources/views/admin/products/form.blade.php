@php($editing = $product->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Product'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Product'" />

    <form method="POST" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Product Details</h2>
                <x-admin.input label="Name" name="name" :value="$product->name" required />
                <x-admin.input label="Tagline" name="tagline" :value="$product->tagline" />
                <x-admin.select label="Owning company" name="company_id" :value="$product->company_id" placeholder="— None —" :options="$companies->pluck('name', 'id')" />
                <x-admin.input label="Website URL" name="website_url" type="url" :value="$product->website_url" />
                <x-admin.textarea label="Short description" name="short_description" :value="$product->short_description" />
                <x-admin.textarea label="Full description" name="description" :value="$product->description" rows="8" />
            </div>

            <div class="card space-y-3 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Key Features</h2>
                <p class="text-xs text-ink-muted">One feature per line.</p>
                <textarea name="features_raw" rows="5" class="form-textarea" onchange="this.form.elements['features[]']?.remove()">{{ implode("\n", $product->features ?? []) }}</textarea>
                @foreach (($product->features ?? []) as $feature)
                    <input type="hidden" name="features[]" value="{{ $feature }}">
                @endforeach
                <p class="form-hint">Features are saved from the list above when you submit this form.</p>
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Categories</h2>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($categories as $category)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" @checked($product->categories->contains($category->id)) class="rounded border-slate-300 text-brand-blue">
                            {{ $category->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">SEO</h2>
                <x-admin.input label="SEO title" name="seo_title" :value="$product->seo_title" />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$product->seo_description" rows="2" />
                <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$product->seoImageMedia" />
                <x-admin.checkbox label="No-index this page" name="no_index" :checked="$product->no_index" />
            </div>
        </div>

        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Publishing</h2>
                <x-admin.select label="Status" name="status" :value="$product->status ?? 'draft'" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" required />
                <x-admin.checkbox label="Feature on homepage" name="is_featured" :checked="$product->is_featured" />
                <x-admin.input type="number" label="Sort order" name="sort_order" :value="$product->sort_order ?? 0" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Imagery</h2>
                <x-admin.media-picker label="Logo" field="logo_media_id" :current="$product->logoMedia" />
                <x-admin.media-picker label="Screenshot" field="screenshot_media_id" :current="$product->screenshotMedia" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn-primary w-full">{{ $editing ? 'Save changes' : 'Create product' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline w-full text-center">Cancel</a>
            </div>
        </div>
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const textarea = this.querySelector('textarea[name="features_raw"]');
            this.querySelectorAll('input[name="features[]"]').forEach((el) => el.remove());
            textarea.value.split('\n').map(s => s.trim()).filter(Boolean).forEach((line) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'features[]';
                input.value = line;
                this.appendChild(input);
            });
        });
    </script>
</x-layouts.admin>
