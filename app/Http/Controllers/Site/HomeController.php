<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $page = Page::query()->where('is_homepage', true)->published()->first();

        if (! $page) {
            return view('site.home-fallback');
        }

        return view('site.page', [
            'page' => $page->load('sections.globalSection'),
        ]);
    }
}
