@php($editing = $company->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Company'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Company'" />

    <form method="POST" action="{{ $editing ? route('admin.companies.update', $company) : route('admin.companies.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="space-y-6 lg:col-span-2">
            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Company Details</h2>
                <x-admin.input label="Name" name="name" :value="$company->name" required />
                <x-admin.input label="Legal name" name="legal_name" :value="$company->legal_name" hint="Full registered legal entity name, if different." />
                <x-admin.textarea label="Short description" name="short_description" :value="$company->short_description" hint="Shown on directory cards (max 500 characters)." />
                <x-admin.textarea label="Full description" name="description" :value="$company->description" rows="8" hint="Rendered on the company detail page. Basic formatting is sanitized and preserved." />
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Classification</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-admin.select label="Type" name="type" :value="$company->type" :options="['holding' => 'Holding', 'subsidiary' => 'Subsidiary', 'division' => 'Business Division', 'joint_venture' => 'Joint Venture']" required />
                    <x-admin.select label="Parent company" name="parent_company_id" :value="$company->parent_company_id" placeholder="— None —" :options="$companies->pluck('name', 'id')" />
                    <x-admin.input label="Category" name="category" :value="$company->category" hint="e.g. Digital Asset Infrastructure & FinTech" />
                    <x-admin.input label="Region" name="region" :value="$company->region" hint="e.g. United Kingdom, Nigeria, Global" />
                    <x-admin.input label="Website URL" name="website_url" type="url" :value="$company->website_url" />
                    <x-admin.input label="Founded year" name="founded_year" type="number" :value="$company->founded_year" />
                </div>
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Linked Services</h2>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    @foreach ($services as $service)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" @checked($company->services->contains($service->id)) class="rounded border-slate-300 text-brand-blue">
                            {{ $service->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card space-y-5 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">SEO</h2>
                <x-admin.input label="SEO title" name="seo_title" :value="$company->seo_title" hint="Defaults to the company name when left blank." />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$company->seo_description" rows="2" />
                <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$company->seoImageMedia" />
                <x-admin.checkbox label="No-index this page" name="no_index" :checked="$company->no_index" hint="Prevents search engines from indexing this company's page." />
            </div>
        </div>

        <div class="space-y-6">
            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Publishing</h2>
                <x-admin.select label="Status" name="status" :value="$company->status ?? 'draft'" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" required />
                <x-admin.checkbox label="Feature on homepage" name="is_featured" :checked="$company->is_featured" />
                <x-admin.input type="number" label="Sort order" name="sort_order" :value="$company->sort_order ?? 0" />
            </div>

            <div class="card space-y-4 p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Brand Imagery</h2>
                <x-admin.media-picker label="Logo" field="logo_media_id" :current="$company->logoMedia" />
                <x-admin.media-picker label="Cover image" field="cover_media_id" :current="$company->coverMedia" />
            </div>

            <div class="flex flex-col gap-2">
                <button type="submit" class="btn-primary w-full">{{ $editing ? 'Save changes' : 'Create company' }}</button>
                <a href="{{ route('admin.companies.index') }}" class="btn-outline w-full text-center">Cancel</a>
            </div>
        </div>
    </form>
</x-layouts.admin>
