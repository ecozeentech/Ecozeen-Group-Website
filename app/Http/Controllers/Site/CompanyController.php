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
            ->where('type', '!=', 'holding')
            ->with('logoMedia')
            ->when(request('q'), fn ($q) => $q->where('name', 'like', '%'.request('q').'%'))
            ->when(request('region'), fn ($q) => $q->where('region', request('region')))
            ->when(request('category'), fn ($q) => $q->where('category', request('category')))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $regions = Company::published()
            ->where('type', '!=', 'holding')
            ->whereNotNull('region')
            ->where('region', '!=', '')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        $categories = Company::published()
            ->where('type', '!=', 'holding')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('site.companies.index', compact('companies', 'regions', 'categories'));
    }

    public function show(Company $company): View
    {
        abort_unless($company->status === 'published', 404);

        return view('site.companies.show', [
            'company' => $company->load('services', 'products', 'subsidiaries', 'projects'),
        ]);
    }
}
