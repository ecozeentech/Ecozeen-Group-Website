<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\ContentRevision;
use App\Models\GlobalSection;
use App\Models\Page;
use App\Services\ActivityLogger;
use App\Services\NavigationService;
use App\Support\PageBuilder\SectionTypeRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PageController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(Page::class, 'page');
    }

    public function index(): View
    {
        $pages = Page::query()
            ->when(request('q'), fn ($q) => $q->where('title', 'like', '%'.request('q').'%'))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $page = DB::transaction(function () use ($data) {
            if (! empty($data['is_homepage'])) {
                Page::where('is_homepage', true)->update(['is_homepage' => false]);
            }

            return Page::create($data);
        });

        ActivityLogger::log('created', $page, "Created page \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page created. Now add sections below.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.builder', [
            'page' => $page->load(['sections.globalSection', 'revisions.creator']),
            'sectionTypes' => SectionTypeRegistry::definitions(),
            'globalSections' => GlobalSection::orderBy('name')->get(),
        ]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $data = $this->prepareData($request, $page);
        $data['updated_by'] = Auth::id();

        DB::transaction(function () use ($data, $page) {
            $this->recordRevision($page, 'Settings updated');

            if (! empty($data['is_homepage'])) {
                Page::where('is_homepage', true)->where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }

            $page->update($data);
        });

        ActivityLogger::log('updated', $page, "Updated page \"{$page->title}\"");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        ActivityLogger::log('deleted', null, "Deleted page \"{$page->title}\"");

        return redirect()->route('admin.pages.index')->with('success', 'Page archived successfully.');
    }

    public function duplicate(Page $page): RedirectResponse
    {
        $this->authorize('create', Page::class);

        $copy = DB::transaction(function () use ($page) {
            $copy = $page->replicate(['slug', 'is_homepage', 'published_at']);
            $copy->title = $page->title.' (Copy)';
            $copy->slug = null;
            $copy->status = 'draft';
            $copy->is_homepage = false;
            $copy->published_at = null;
            $copy->created_by = Auth::id();
            $copy->updated_by = Auth::id();
            $copy->save();

            foreach ($page->sections as $section) {
                $copy->sections()->create([
                    'global_section_id' => $section->global_section_id,
                    'type' => $section->type,
                    'settings' => $section->settings,
                    'position' => $section->position,
                    'is_visible' => $section->is_visible,
                    'visible_desktop' => $section->visible_desktop,
                    'visible_mobile' => $section->visible_mobile,
                ]);
            }

            return $copy;
        });

        ActivityLogger::log('created', $copy, "Duplicated page \"{$page->title}\" as \"{$copy->title}\"");

        return redirect()->route('admin.pages.edit', $copy)->with('success', 'Page duplicated. Review and publish when ready.');
    }

    public function publish(Page $page): RedirectResponse
    {
        $this->authorize('publish', $page);

        $page->update(['status' => 'published', 'published_at' => $page->published_at ?: now()]);
        NavigationService::flush();

        ActivityLogger::log('published', $page, "Published page \"{$page->title}\"");

        return back()->with('success', 'Page published.');
    }

    public function unpublish(Page $page): RedirectResponse
    {
        $this->authorize('publish', $page);

        $page->update(['status' => 'draft']);
        NavigationService::flush();

        ActivityLogger::log('unpublished', $page, "Unpublished page \"{$page->title}\"");

        return back()->with('success', 'Page unpublished and reverted to draft.');
    }

    public function restoreRevision(Page $page, ContentRevision $revision): RedirectResponse
    {
        $this->authorize('update', $page);

        if ($revision->revisionable_type !== $page->getMorphClass() || $revision->revisionable_id !== $page->id) {
            abort(404);
        }

        DB::transaction(function () use ($page, $revision) {
            $this->recordRevision($page, 'Snapshot before restoring revision #'.$revision->id);

            $data = $revision->data;
            $page->update(collect($data['page'] ?? [])->except(['id', 'created_at', 'updated_at', 'deleted_at'])->toArray());

            $page->sections()->delete();
            foreach ($data['sections'] ?? [] as $sectionData) {
                $page->sections()->create([
                    'global_section_id' => $sectionData['global_section_id'] ?? null,
                    'type' => $sectionData['type'],
                    'settings' => $sectionData['settings'] ?? [],
                    'position' => $sectionData['position'] ?? 0,
                    'is_visible' => $sectionData['is_visible'] ?? true,
                    'visible_desktop' => $sectionData['visible_desktop'] ?? true,
                    'visible_mobile' => $sectionData['visible_mobile'] ?? true,
                ]);
            }
        });

        ActivityLogger::log('restored', $page, "Restored page \"{$page->title}\" to a previous revision");

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Revision restored.');
    }

    private function recordRevision(Page $page, string $summary): void
    {
        ContentRevision::create([
            'revisionable_type' => $page->getMorphClass(),
            'revisionable_id' => $page->id,
            'data' => [
                'page' => $page->toArray(),
                'sections' => $page->sections()->orderBy('position')->get()->toArray(),
            ],
            'summary' => $summary,
            'created_by' => Auth::id(),
            'created_at' => now(),
        ]);

        // Keep only the most recent 20 revisions per page to bound storage.
        $staleIds = ContentRevision::where('revisionable_type', $page->getMorphClass())
            ->where('revisionable_id', $page->id)
            ->orderByDesc('created_at')
            ->skip(20)->take(1000)->pluck('id');

        if ($staleIds->isNotEmpty()) {
            ContentRevision::whereIn('id', $staleIds)->delete();
        }
    }

    private function prepareData(PageRequest $request, ?Page $page = null): array
    {
        $data = $request->validated();
        unset($data['seo_image_media_id_upload']);

        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo', $page?->seo_image_media_id);
        $data['is_homepage'] = $request->boolean('is_homepage');
        $data['no_index'] = $request->boolean('no_index');

        if (empty($data['slug'])) {
            unset($data['slug']);
        }

        return $data;
    }
}
