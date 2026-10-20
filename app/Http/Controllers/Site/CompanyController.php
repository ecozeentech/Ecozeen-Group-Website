<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::query()
            ->published()
            ->whereNull('parent_company_id')
            ->with('subsidiaries')
            ->when(request('region'), fn ($q) => $q->where('region', request('region')))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $regions = Company::published()->whereNotNull('region')->distinct()->pluck('region');

        return view('site.companies.index', compact('companies', 'regions'));
    }

    public function show(Company $company): View
    {
        abort_unless($company->status === 'published', 404);

        return view('site.companies.show', [
            'company' => $company->load('services', 'products', 'subsidiaries', 'projects'),
        ]);
    }
}
