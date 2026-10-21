<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()->where('slug', $slug)->where('is_homepage', false)->firstOrFail();

        abort_unless($page->isPubliclyVisible(), 404);

        $page->load('sections.globalSection');

        $hero = $page->sections->first(fn ($section) => $section->is_visible && $section->type === 'hero');
        $mission = $page->sections->first(fn ($section) => $section->is_visible && $section->type === 'mission_vision');
        $values = $page->sections->first(fn ($section) => $section->is_visible && $section->type === 'core_values');
        $rich = $page->sections->first(fn ($section) => $section->is_visible && $section->type === 'rich_text');
        $features = $page->sections->first(fn ($section) => $section->is_visible && $section->type === 'feature_grid');

        if ($slug === 'about') {
            return view('site.about', [
                'page' => $page,
                'hero' => $hero?->effectiveSettings() ?? [],
                'mission' => $mission?->effectiveSettings() ?? [],
                'values' => $values?->effectiveSettings() ?? [],
                'intro' => $rich?->effectiveSettings() ?? [],
                'companies' => Company::query()->published()->where('type', '!=', 'holding')->orderBy('sort_order')->get(),
                'companyCount' => Company::query()->published()->where('type', '!=', 'holding')->count(),
            ]);
        }

        if ($slug === 'partner') {
            return view('site.partner', [
                'page' => $page,
                'hero' => $hero?->effectiveSettings() ?? [],
                'features' => $features?->effectiveSettings() ?? [],
                'companies' => Company::query()->published()->orderBy('name')->get(),
                'companyCount' => Company::query()->published()->where('type', '!=', 'holding')->count(),
            ]);
        }

        return view('site.page', [
            'page' => $page,
        ]);
    }
}
