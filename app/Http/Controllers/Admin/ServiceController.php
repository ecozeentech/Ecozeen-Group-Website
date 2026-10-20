<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Company;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(): View
    {
        $services = Service::query()
            ->with('category')
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%'))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(20)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return $this->formView(new Service);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        [$fields, $companyIds] = $this->prepareData($request);
        $service = Service::create($fields);
        $service->companies()->sync($companyIds);

        ActivityLogger::log('created', $service, "Created service {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        return $this->formView($service);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        [$fields, $companyIds] = $this->prepareData($request);
        $service->update($fields);
        $service->companies()->sync($companyIds);

        ActivityLogger::log('updated', $service, "Updated service {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        ActivityLogger::log('deleted', null, "Deleted service {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service archived successfully.');
    }

    private function formView(Service $service): View
    {
        return view('admin.services.form', [
            'service' => $service,
            'categories' => ServiceCategory::orderBy('name')->get(),
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    private function prepareData(ServiceRequest $request): array
    {
        $data = $request->validated();
        $companyIds = $data['company_ids'] ?? [];
        unset($data['company_ids'], $data['cover_media_id_upload'], $data['seo_image_media_id_upload']);

        $data['cover_media_id'] = $this->resolveMediaId($request, 'cover_media_id', 'services');
        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        return [$data, $companyIds];
    }
}
