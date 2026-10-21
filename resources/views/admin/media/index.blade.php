<x-layouts.admin title="Media Library">
    <x-admin.page-header title="Media Library" description="Upload and manage images and documents used throughout the site." />

    @can('media.upload')
        <div class="card mb-6 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Upload files</h2>
            <form
                method="POST"
                action="{{ route('admin.media.store') }}"
                enctype="multipart/form-data"
                class="mt-4 space-y-4"
                x-data="{ dragging: false, label: 'Choose files or drop them here' }"
            >
                @csrf
                <label
                    for="files"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed px-6 py-10 text-center transition"
                    :class="dragging ? 'border-brand-blue bg-blue-50' : 'border-slate-300 bg-slate-50 hover:border-brand-blue'"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="
                        dragging = false;
                        $refs.fileInput.files = $event.dataTransfer.files;
                        label = $event.dataTransfer.files.length + ' file(s) selected';
                    "
                >
                    <x-icon name="cloud_upload" class="text-brand-blue text-[36px]" />
                    <span class="mt-3 text-sm font-semibold text-ink" x-text="label">Choose files or drop them here</span>
                    <span class="mt-1 text-xs text-ink-muted">Images: JPEG, PNG, WEBP, GIF, SVG up to 8 MB. Documents: PDF up to 10 MB.</span>
                    <input
                        type="file"
                        id="files"
                        name="files[]"
                        x-ref="fileInput"
                        multiple
                        required
                        accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml,application/pdf"
                        class="sr-only"
                        @change="label = $event.target.files.length + ' file(s) selected'"
                    >
                </label>
                @error('files')<p class="form-error">{{ $message }}</p>@enderror
                @error('files.*')<p class="form-error">{{ $message }}</p>@enderror
                @error('file')<p class="form-error">{{ $message }}</p>@enderror
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="w-full sm:w-56">
                        <label for="collection" class="form-label">Collection (optional)</label>
                        <input type="text" id="collection" name="collection" placeholder="e.g. blog" class="form-input">
                    </div>
                    <button type="submit" class="btn-primary">Upload</button>
                </div>
            </form>
        </div>
    @endcan

    <x-admin.filter-bar placeholder="Search filenames...">
        <select name="type" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All types</option>
            <option value="image" @selected(request('type') === 'image')>Images</option>
            <option value="document" @selected(request('type') === 'document')>Documents</option>
        </select>
    </x-admin.filter-bar>

    @if ($media->isEmpty())
        <x-admin.empty-state title="No media uploaded yet" description="Upload your first image or document above." />
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($media as $asset)
                <div class="card overflow-hidden" x-data="{ editing: false }">
                    <div class="flex h-32 items-center justify-center bg-slate-50">
                        @if ($asset->isImage())
                            <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text }}" class="h-full w-full object-cover">
                        @else
                            <x-icon name="picture_as_pdf" class="text-[40px] text-slate-400" />
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="truncate text-xs font-medium text-ink" title="{{ $asset->original_filename }}">{{ $asset->original_filename }}</p>
                        <p class="text-xs text-ink-muted">{{ $asset->humanSize() }} @if($asset->width) &middot; {{ $asset->width }}&times;{{ $asset->height }} @endif</p>

                        <button type="button" class="mt-2 text-xs font-medium text-brand-blue" @click="editing = !editing">Edit details</button>

                        <form method="POST" action="{{ route('admin.media.update', $asset) }}" x-show="editing" x-cloak class="mt-2 space-y-2">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="alt_text" value="{{ $asset->alt_text }}" placeholder="Alt text" class="form-input text-xs">
                            <input type="text" name="caption" value="{{ $asset->caption }}" placeholder="Caption" class="form-input text-xs">
                            <button type="submit" class="btn-outline w-full text-xs">Save</button>
                        </form>

                        @can('media.delete')
                            <div class="mt-2">
                                <x-admin.delete-button :action="route('admin.media.destroy', $asset)" label="Delete" class="w-full text-xs" confirm="Delete this file? This cannot be undone and will fail if it is still referenced elsewhere." />
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $media->links() }}</div>
    @endif
</x-layouts.admin>
