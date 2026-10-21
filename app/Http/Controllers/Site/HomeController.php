<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::query()->where('is_homepage', true)->published()->with('sections.globalSection')->first();

        $hero = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'hero');
        $mission = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'mission_vision');
        $values = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'core_values');

        $companies = Company::query()
            ->published()
            ->where('type', '!=', 'holding')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $products = Product::query()
            ->published()
            ->with('company')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $services = Service::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $posts = BlogPost::query()
            ->published()
            ->with('category')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('site.home', [
            'page' => $page,
            'hero' => $hero?->effectiveSettings() ?? [],
            'mission' => $mission?->effectiveSettings() ?? [],
            'values' => $values?->effectiveSettings() ?? [],
            'companies' => $companies,
            'products' => $products,
            'services' => $services,
            'posts' => $posts,
            'companyCount' => Company::query()->published()->where('type', '!=', 'holding')->count(),
            'productCount' => Product::query()->published()->count(),
            'inquiryCompanies' => Company::query()->published()->orderBy('name')->get(),
        ]);
    }
}
