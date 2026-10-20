<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyRequest;
use App\Models\Company;
use App\Models\Service;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    use HandlesMediaUploads;

    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    public function index(): View
    {
        $companies = Company::query()
            ->with('parentCompany')
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%'))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.form', [
            'company' => new Company,
            'companies' => Company::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        [$fields, $serviceIds] = $this->prepareData($request);

        $company = Company::create($fields);
        $company->services()->sync($serviceIds);

        ActivityLogger::log('created', $company, "Created company {$company->name}");

        return redirect()->route('admin.companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.form', [
            'company' => $company,
            'companies' => Company::where('id', '!=', $company->id)->orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        [$fields, $serviceIds] = $this->prepareData($request);

        $company->update($fields);
        $company->services()->sync($serviceIds);

        ActivityLogger::log('updated', $company, "Updated company {$company->name}");

        return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        ActivityLogger::log('deleted', null, "Deleted company {$company->name}");

        return redirect()->route('admin.companies.index')->with('success', 'Company archived successfully.');
    }

    private function prepareData(CompanyRequest $request): array
    {
        $data = $request->validated();
        $serviceIds = $data['service_ids'] ?? [];
        unset($data['service_ids'], $data['logo_media_id_upload'], $data['cover_media_id_upload'], $data['seo_image_media_id_upload']);

        $data['logo_media_id'] = $this->resolveMediaId($request, 'logo_media_id', 'companies');
        $data['cover_media_id'] = $this->resolveMediaId($request, 'cover_media_id', 'companies');
        $data['seo_image_media_id'] = $this->resolveMediaId($request, 'seo_image_media_id', 'seo');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['no_index'] = $request->boolean('no_index');

        return [$data, $serviceIds];
    }
}
