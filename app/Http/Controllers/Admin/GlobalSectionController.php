<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GlobalSectionRequest;
use App\Models\GlobalSection;
use App\Services\ActivityLogger;
use App\Support\PageBuilder\SectionTypeRegistry;
use App\Http\Controllers\Admin\PageSectionController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GlobalSectionController extends Controller
{
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
        ]);

        $globalSection->update([
            'name' => $data['name'],
            'settings' => SectionTypeRegistry::sanitize(
                $globalSection->type,
                PageSectionController::decodeJsonFields($globalSection->type, $data['settings'] ?? [])
            ),
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
