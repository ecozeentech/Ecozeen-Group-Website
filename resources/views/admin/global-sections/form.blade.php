@php($editing = $section->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Reusable Section'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Reusable Section'" />

    @if (! $editing)
        <form method="POST" action="{{ route('admin.global-sections.store') }}" class="card max-w-xl space-y-5 p-6">
            @csrf
            <x-admin.input label="Name" name="name" required hint="An internal label, e.g. 'Standard Partnership CTA'." />
            <x-admin.input label="Identifier" name="identifier" required hint="A unique machine identifier, e.g. 'standard-partnership-cta'." />
            <x-admin.select label="Section type" name="type" :options="collect($sectionTypes)->map(fn ($d) => $d['label'])->all()" required />
            <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
                <a href="{{ route('admin.global-sections.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Create &amp; Configure</button>
            </div>
        </form>
    @else
        <form method="POST" action="{{ route('admin.global-sections.update', $section) }}" class="card max-w-2xl space-y-5 p-6">
            @csrf
            @method('PUT')
            <x-admin.input label="Name" name="name" :value="$section->name" required />
            <p class="text-sm text-ink-muted">Type: {{ \App\Support\PageBuilder\SectionTypeRegistry::label($section->type) }} (identifier: <code>{{ $section->identifier }}</code>)</p>

            <div class="border-t border-surface-border pt-4">
                @include('admin.pages._section-fields', [
                    'type' => $section->type,
                    'fields' => $sectionTypes[$section->type]['fields'] ?? [],
                    'settings' => $section->settings ?? [],
                ])
            </div>

            <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
                <a href="{{ route('admin.global-sections.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Reusable Section</button>
            </div>
        </form>
    @endif
</x-layouts.admin>
