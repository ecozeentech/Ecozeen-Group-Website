<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Company;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(): View
    {
        $projects = Project::query()
            ->with('company', 'category')
            ->when(request('q'), fn ($q) => $q->where('title', 'like', '%'.request('q').'%'))
            ->orderBy('sort_order')->orderBy('title')
            ->paginate(20)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return $this->formView(new Project);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        [$fields, $serviceIds, $galleryIds] = $this->prepareData($request);

        $project = Project::create($fields);
        $project->services()->sync($serviceIds);
        $this->syncGallery($project, $galleryIds);

        ActivityLogger::log('created', $project, "Created project {$project->title}");

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        return $this->formView($project);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        [$fields, $serviceIds, $galleryIds] = $this->prepareData($request, $project);

        $project->update($fields);
        $project->services()->sync($serviceIds);
        $this->syncGallery($project, $galleryIds);

        ActivityLogger::log('updated', $project, "Updated project {$project->title}");

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        ActivityLogger::log('deleted', null, "Deleted project {$project->title}");

        return redirect()->route('admin.projects.index')->with('success', 'Project archived successfully.');
    }

    private function formView(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'companies' => Company::orderBy('name')->get(),
            'categories' => ProjectCategory::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
            'mediaLibrary' => $this->galleryLibrary($project),
        ]);
    }

    private function galleryLibrary(Project $project)
    {
        $library = MediaAsset::query()->where('mime_type', 'like', 'image/%')->latest()->limit(100)->get();

        if ($project->exists) {
            $library = $project->galleryMedia->concat($library)->unique('id')->values();
        }

        return $library;
    }

    private function syncGallery(Project $project, array $galleryIds): void
    {
        $project->galleryMedia()->detach();
        foreach (array_values($galleryIds) as $index => $mediaId) {
            $project->galleryMedia()->attach($mediaId, ['sort_order' => $index]);
        }
    }

    private function prepareData(ProjectRequest $request, ?Project $project = null): array
    {
        $data = $request->validated();
        $serviceIds = $data['service_ids'] ?? [];
        $galleryIds = $request->has('gallery_media_ids')
            ? ($data['gallery_media_ids'] ?? [])
            : ($project?->galleryMedia->pluck('id')->all() ?? []);
        unset($data['service_ids'], $data['gallery_media_ids'], $data['cover_media_id_upload'], $data['seo_image_media_id_upload']);

        $data['cover_media_id'] = $this->resolveMediaId($request, 'cover_media_id', 'projects', $project?->cover_media_id);
        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo', $project?->seo_image_media_id);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        return [$data, $serviceIds, $galleryIds];
    }
}
