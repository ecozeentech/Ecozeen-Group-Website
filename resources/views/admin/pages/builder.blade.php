<x-layouts.admin :title="'Edit — '.$page->title">
    <x-admin.page-header :title="$page->title" description="Configure page settings and build its sections.">
        <x-slot:actions>
            @if ($page->status === 'published')
                <a href="{{ $page->is_homepage ? route('home') : route('pages.show', $page->slug) }}" target="_blank" class="btn-outline">View Live</a>
            @endif
            <a href="{{ route('admin.pages.index') }}" class="btn-outline">Back to Pages</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- Sections --}}
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-heading text-sm font-semibold text-ink">Sections ({{ $page->sections->count() }})</h2>
                    <x-admin.status-badge :status="$page->status" />
                </div>

                @if ($page->sections->isEmpty())
                    <p class="mt-4 text-sm text-ink-muted">No sections yet. Add your first section below.</p>
                @else
                    <div class="mt-4 space-y-3" x-data="{ open: null }">
                        @php($orderedIds = $page->sections->pluck('id')->values())
                        @foreach ($page->sections as $section)
                            @php($currentIndex = $orderedIds->search($section->id))
                            <div class="rounded-token border border-surface-border">
                                <div class="flex items-center justify-between gap-2 p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="badge badge-neutral">{{ \App\Support\PageBuilder\SectionTypeRegistry::label($section->type) }}</span>
                                        @if ($section->globalSection)
                                            <span class="text-xs text-ink-muted">Reusable: {{ $section->globalSection->name }}</span>
                                        @endif
                                        @unless ($section->is_visible)
                                            <span class="badge badge-neutral">Hidden</span>
                                        @endunless
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($currentIndex > 0)
                                            @php($swapped = $orderedIds->toArray())
                                            @php([$swapped[$currentIndex - 1], $swapped[$currentIndex]] = [$swapped[$currentIndex], $swapped[$currentIndex - 1]])
                                            <form method="POST" action="{{ route('admin.pages.sections.reorder', $page) }}">
                                                @csrf
                                                @foreach ($swapped as $id)
                                                    <input type="hidden" name="order[]" value="{{ $id }}">
                                                @endforeach
                                                <button type="submit" class="btn-outline" aria-label="Move section up">&uarr;</button>
                                            </form>
                                        @endif
                                        @if ($currentIndex < $orderedIds->count() - 1)
                                            @php($swapped = $orderedIds->toArray())
                                            @php([$swapped[$currentIndex + 1], $swapped[$currentIndex]] = [$swapped[$currentIndex], $swapped[$currentIndex + 1]])
                                            <form method="POST" action="{{ route('admin.pages.sections.reorder', $page) }}">
                                                @csrf
                                                @foreach ($swapped as $id)
                                                    <input type="hidden" name="order[]" value="{{ $id }}">
                                                @endforeach
                                                <button type="submit" class="btn-outline" aria-label="Move section down">&darr;</button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn-outline" @click="open = open === {{ $section->id }} ? null : {{ $section->id }}">
                                            <span x-text="open === {{ $section->id }} ? 'Close' : 'Configure'"></span>
                                        </button>
                                        <x-admin.delete-button :action="route('admin.pages.sections.destroy', [$page, $section])" label="Remove" confirm="Remove this section from the page?" />
                                    </div>
                                </div>

                                <div x-show="open === {{ $section->id }}" x-cloak class="border-t border-surface-border p-4">
                                    @if ($section->globalSection)
                                        <p class="mb-4 rounded-token border border-blue-200 bg-blue-50 px-3 py-2 text-xs text-blue-700">
                                            This is a reusable section. Edit it from
                                            <a href="{{ route('admin.global-sections.edit', $section->globalSection) }}" class="font-semibold underline">Reusable Sections</a>
                                            — changes apply everywhere it's placed.
                                        </p>
                                    @else
                                        <form method="POST" action="{{ route('admin.pages.sections.update', [$page, $section]) }}">
                                            @csrf
                                            @method('PUT')
                                            @include('admin.pages._section-fields', [
                                                'type' => $section->type,
                                                'fields' => $sectionTypes[$section->type]['fields'] ?? [],
                                                'settings' => $section->settings ?? [],
                                            ])
                                            <div class="mt-4 flex flex-wrap items-center gap-4 border-t border-surface-border pt-4">
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="is_visible" value="1" @checked($section->is_visible) class="rounded border-slate-300 text-brand-blue"> Visible
                                                </label>
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="visible_desktop" value="1" @checked($section->visible_desktop) class="rounded border-slate-300 text-brand-blue"> Show on desktop
                                                </label>
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="visible_mobile" value="1" @checked($section->visible_mobile) class="rounded border-slate-300 text-brand-blue"> Show on mobile
                                                </label>
                                                <button type="submit" class="btn-primary ml-auto">Save Section</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-6 border-t border-surface-border pt-4">
                    <h3 class="font-heading text-sm font-semibold text-ink">Add a Section</h3>
                    <form method="POST" action="{{ route('admin.pages.sections.store', $page) }}" class="mt-3 flex flex-col gap-3 sm:flex-row">
                        @csrf
                        <select name="type" required class="form-select flex-1">
                            @foreach ($sectionTypes as $typeKey => $definition)
                                <option value="{{ $typeKey }}">{{ $definition['label'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary whitespace-nowrap">+ Add Section</button>
                    </form>

                    @if ($globalSections->isNotEmpty())
                        <form method="POST" action="{{ route('admin.pages.sections.store', $page) }}" class="mt-3 flex flex-col gap-3 sm:flex-row">
                            @csrf
                            <select name="global_section_id" required class="form-select flex-1" onchange="this.form.querySelector('[name=type]').value = this.selectedOptions[0].dataset.type">
                                <option value="">— Insert a reusable section —</option>
                                @foreach ($globalSections as $gs)
                                    <option value="{{ $gs->id }}" data-type="{{ $gs->type }}">{{ $gs->name }} ({{ \App\Support\PageBuilder\SectionTypeRegistry::label($gs->type) }})</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="type" value="">
                            <button type="submit" class="btn-outline whitespace-nowrap">+ Insert Reusable</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Revision history --}}
            <div class="card p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Revision History</h2>
                @if ($page->revisions->isEmpty())
                    <p class="mt-3 text-sm text-ink-muted">No saved revisions yet. A snapshot is saved automatically each time this page is updated.</p>
                @else
                    <ul class="mt-3 divide-y divide-surface-border">
                        @foreach ($page->revisions->take(10) as $revision)
                            <li class="flex items-center justify-between gap-3 py-3 text-sm">
                                <span>{{ $revision->summary }} &mdash; <span class="text-ink-muted">{{ $revision->created_at?->diffForHumans() }} by {{ $revision->creator?->name ?? 'System' }}</span></span>
                                <form method="POST" action="{{ route('admin.pages.restore-revision', [$page, $revision]) }}" onsubmit="return confirm('Restore the page to this revision? This will overwrite current content.')">
                                    @csrf
                                    <button type="submit" class="btn-outline">Restore</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="card p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Page Settings</h2>
                <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    <x-admin.input label="Title" name="title" :value="$page->title" required />
                    <x-admin.input label="Slug" name="slug" :value="$page->slug" />
                    <x-admin.select label="Template" name="template" :value="$page->template" :options="['default' => 'Default', 'landing' => 'Landing Page', 'full-width' => 'Full Width']" required />
                    <x-admin.select label="Status" name="status" :value="$page->status" :options="['draft' => 'Draft', 'in_review' => 'In Review', 'scheduled' => 'Scheduled', 'published' => 'Published', 'archived' => 'Archived']" required />
                    <x-admin.input type="datetime-local" label="Scheduled publish time" name="scheduled_at" :value="$page->scheduled_at?->format('Y-m-d\TH:i')" hint="Used by the publishing scheduler (see docs) when status is Scheduled." />
                    <x-admin.checkbox label="Set as homepage" name="is_homepage" :checked="$page->is_homepage" />

                    <div class="border-t border-surface-border pt-4">
                        <h3 class="font-heading text-xs font-semibold uppercase tracking-wide text-ink-muted">SEO</h3>
                        <div class="mt-3 space-y-4">
                            <x-admin.input label="SEO title" name="seo_title" :value="$page->seo_title" />
                            <x-admin.textarea label="SEO description" name="seo_description" :value="$page->seo_description" rows="2" />
                            <x-admin.input label="Canonical URL" name="canonical_url" :value="$page->canonical_url" />
                            <x-admin.media-picker label="Social sharing image" field="seo_image_media_id" :current="$page->seoImageMedia" />
                            <x-admin.checkbox label="No-index this page" name="no_index" :checked="$page->no_index" />
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">Save Page Settings</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
