<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GlobalSectionRequest;
use App\Models\GlobalSection;
use App\Services\ActivityLogger;
use App\Support\PageBuilder\SectionTypeRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GlobalSectionController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        $this->authorize('pages.view');

        $sections = GlobalSection::orderBy('name')->paginate(20);

        return view('admin.global-sections.index', compact('sections'));
    }

    public function create(): View
    {
        $this->authorize('pages.update');

        return view('admin.global-sections.form', [
            'section' => new GlobalSection,
            'sectionTypes' => SectionTypeRegistry::definitions(),
        ]);
    }

    public function store(GlobalSectionRequest $request): RedirectResponse
    {
        $section = GlobalSection::create($request->validated() + ['settings' => []]);
        ActivityLogger::log('created', $section, "Created reusable section \"{$section->name}\"");

        return redirect()->route('admin.global-sections.edit', $section)->with('success', 'Reusable section created. Configure it below.');
    }

    public function edit(GlobalSection $globalSection): View
    {
        $this->authorize('pages.update');

        return view('admin.global-sections.form', [
            'section' => $globalSection,
            'sectionTypes' => SectionTypeRegistry::definitions(),
        ]);
    }

    public function update(Request $request, GlobalSection $globalSection): RedirectResponse
    {
        $this->authorize('pages.update');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'settings' => ['nullable', 'array'],
            'image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'image_media_id_upload' => ['nullable', 'image', 'max:8192'],
        ]);

        $settings = $this->mergeSectionImageMedia(
            $request,
            PageSectionController::decodeJsonFields($globalSection->type, $data['settings'] ?? []),
            isset($globalSection->settings['image_media_id']) ? (int) $globalSection->settings['image_media_id'] : null,
        );

        $globalSection->update([
            'name' => $data['name'],
            'settings' => SectionTypeRegistry::sanitize($globalSection->type, $settings),
        ]);

        ActivityLogger::log('updated', $globalSection, "Updated reusable section \"{$globalSection->name}\"");

        return redirect()->route('admin.global-sections.edit', $globalSection)->with('success', 'Reusable section updated. Changes apply everywhere it is used.');
    }

    public function destroy(GlobalSection $globalSection): RedirectResponse
    {
        $this->authorize('pages.update');

        if ($globalSection->pageSections()->exists()) {
            return back()->with('error', 'This reusable section is still placed on one or more pages and cannot be deleted.');
        }

        $name = $globalSection->name;
        $globalSection->delete();

        ActivityLogger::log('deleted', null, "Deleted reusable section \"{$name}\"");

        return redirect()->route('admin.global-sections.index')->with('success', 'Reusable section deleted.');
    }
}
