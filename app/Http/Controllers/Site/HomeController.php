<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Product;
use App\Models\WebsiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::query()->where('is_homepage', true)->published()->with('sections.globalSection')->first();

        $hero = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'hero');
        $mission = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'mission_vision');
        $values = $page?->sections->first(fn ($section) => $section->is_visible && $section->type === 'core_values');

        $heroSettings = $hero?->effectiveSettings() ?? [];
        $heroImage = null;
        if (! empty($heroSettings['image_media_id'])) {
            $heroImage = MediaAsset::query()->find($heroSettings['image_media_id']);
        }

        $companies = Company::query()
            ->published()
            ->where('type', '!=', 'holding')
            ->with('logoMedia')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $products = Product::query()
            ->published()
            ->with(['company', 'logoMedia', 'screenshotMedia'])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $posts = BlogPost::query()
            ->published()
            ->with(['category', 'coverMedia', 'author'])
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('site.home', [
            'page' => $page,
            'hero' => $heroSettings,
            'heroImage' => $heroImage,
            'mission' => $mission?->effectiveSettings() ?? [],
            'values' => $values?->effectiveSettings() ?? [],
            'companies' => $companies,
            'products' => $products,
            'posts' => $posts,
            'companyCount' => Company::query()->published()->where('type', '!=', 'holding')->count(),
            'productCount' => Product::query()->published()->count(),
            'inquiryCompanies' => Company::query()->published()->orderBy('name')->get(),
            'settings' => WebsiteSetting::current(),
            'defaultPillars' => $this->defaultPillars(),
        ]);
    }

    /**
     * @return list<array{title: string, description: string, icon: string}>
     */
    private function defaultPillars(): array
    {
        return [
            [
                'title' => 'Integrity',
                'icon' => 'balance',
                'description' => 'Uncompromising adherence to regulatory compliance, investor transparency, and institutional ethics.',
            ],
            [
                'title' => 'Innovation',
                'icon' => 'psychology',
                'description' => 'Pioneering proprietary software, high-throughput financial rails, and sustainable technology.',
            ],
            [
                'title' => 'Customer Focus',
                'icon' => 'groups',
                'description' => 'Centering user safety, transactional liquidity, and friction-free enterprise client journeys.',
            ],
            [
                'title' => 'Excellence',
                'icon' => 'verified_user',
                'description' => 'World-class engineering standards, zero-tolerance downtime, and continuous product iteration.',
            ],
            [
                'title' => 'Growth',
                'icon' => 'trending_up',
                'description' => 'Fostering compounding value for stakeholders, employees, and the regional ecosystems we touch.',
            ],
        ];
    }
}
