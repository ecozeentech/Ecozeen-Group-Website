<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\ActivityLogger;
use App\Support\PageBuilder\SectionTypeRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * All section settings submitted from the browser are re-validated and
 * sanitized server-side via SectionTypeRegistry::sanitize() before being
 * persisted. The browser-supplied JSON is never trusted as-is.
 */
class PageSectionController extends Controller
{
    use HandlesMediaUploads;

    public function store(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $data = $request->validate([
            'type' => ['required', Rule::in(SectionTypeRegistry::types())],
            'global_section_id' => ['nullable', 'integer', 'exists:global_sections,id'],
        ]);

        $position = (int) $page->sections()->max('position') + 1;

        $section = $page->sections()->create([
            'global_section_id' => $data['global_section_id'] ?? null,
            'type' => $data['type'],
            'settings' => [],
            'position' => $position,
        ]);

        ActivityLogger::log('created', $page, "Added a {$section->type} section to \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Section added. Configure it below.');
    }

    public function update(Request $request, Page $page, PageSection $section): RedirectResponse
    {
        $this->authorize('update', $page);
        abort_unless($section->page_id === $page->id, 404);

        $validated = $request->validate([
            'settings' => ['nullable', 'array'],
            'is_visible' => ['nullable', 'boolean'],
            'visible_desktop' => ['nullable', 'boolean'],
            'visible_mobile' => ['nullable', 'boolean'],
            'image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'image_media_id_upload' => ['nullable', 'image', 'max:8192'],
        ]);

        $settings = $this->mergeSectionImageMedia(
            $request,
            $this->decodeJsonFields($section->type, $validated['settings'] ?? []),
            isset($section->settings['image_media_id']) ? (int) $section->settings['image_media_id'] : null,
        );

        $section->update([
            'settings' => SectionTypeRegistry::sanitize($section->type, $settings),
            'is_visible' => $request->boolean('is_visible'),
            'visible_desktop' => $request->boolean('visible_desktop'),
            'visible_mobile' => $request->boolean('visible_mobile'),
        ]);

        ActivityLogger::log('updated', $page, "Updated a {$section->type} section on \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Section updated.');
    }

    public function destroy(Page $page, PageSection $section): RedirectResponse
    {
        $this->authorize('update', $page);
        abort_unless($section->page_id === $page->id, 404);

        $section->delete();
        ActivityLogger::log('deleted', $page, "Removed a {$section->type} section from \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Section removed.');
    }

    public function reorder(Request $request, Page $page): RedirectResponse
    {
        $this->authorize('update', $page);

        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:page_sections,id'],
        ]);

        DB::transaction(function () use ($data, $page) {
            foreach ($data['order'] as $position => $sectionId) {
                $page->sections()->where('id', $sectionId)->update(['position' => $position]);
            }
        });

        ActivityLogger::log('updated', $page, "Reordered sections on \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Section order saved.');
    }

    /**
     * The admin section editor submits "list"-type fields (e.g. FAQ items,
     * feature grid items) as a JSON string textarea for simplicity. Decode
     * those into real arrays here; SectionTypeRegistry::sanitize() still
     * performs the authoritative server-side validation afterwards.
     */
    public static function decodeJsonFields(string $type, array $settings): array
    {
        $definition = SectionTypeRegistry::definitions()[$type] ?? ['fields' => []];

        foreach ($definition['fields'] as $field => $fieldType) {
            if (in_array($fieldType, ['list', 'collection'], true) && array_key_exists($field, $settings) && is_string($settings[$field])) {
                $raw = trim($settings[$field]);
                if ($raw === '') {
                    $settings[$field] = [];

                    continue;
                }

                $decoded = json_decode($raw, true);
                if (! is_array($decoded)) {
                    throw ValidationException::withMessages([
                        "settings.{$field}" => 'This field must be valid JSON.',
                    ]);
                }

                $settings[$field] = $decoded;
            }
        }

        return $settings;
    }
}
