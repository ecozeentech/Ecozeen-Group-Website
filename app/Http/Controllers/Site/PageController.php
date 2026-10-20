<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()->where('slug', $slug)->where('is_homepage', false)->firstOrFail();

        abort_unless($page->isPubliclyVisible(), 404);

        return view('site.page', [
            'page' => $page->load('sections.globalSection'),
        ]);
    }
}
