<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Page;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use App\Models\WebsiteSetting;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap(ResponseFactory $responseFactory): Response
    {
        $settings = WebsiteSetting::current();

        $xml = Cache::remember('sitemap.xml', config('ecozeen.seo.sitemap_cache_minutes', 60) * 60, function () use ($settings) {
            if (! $settings->sitemap_enabled) {
                return null;
            }

            $urls = collect();

            $urls->push(['url' => url('/'), 'updated' => now()]);

            Page::published()->where('no_index', false)->get()->each(
                fn (Page $page) => $urls->push(['url' => url($page->is_homepage ? '/' : $page->slug), 'updated' => $page->updated_at])
            );

            Company::published()->where('no_index', false)->get()->each(
                fn (Company $c) => $urls->push(['url' => route('companies.show', $c), 'updated' => $c->updated_at])
            );

            Product::published()->where('no_index', false)->get()->each(
                fn (Product $p) => $urls->push(['url' => route('products.show', $p), 'updated' => $p->updated_at])
            );

            Service::published()->where('no_index', false)->get()->each(
                fn (Service $s) => $urls->push(['url' => route('services.show', $s), 'updated' => $s->updated_at])
            );

            Project::published()->where('no_index', false)->get()->each(
                fn (Project $p) => $urls->push(['url' => route('portfolio.show', $p), 'updated' => $p->updated_at])
            );

            BlogPost::published()->where('no_index', false)->get()->each(
                fn (BlogPost $b) => $urls->push(['url' => route('blog.show', $b), 'updated' => $b->updated_at])
            );

            return view('site.sitemap', ['urls' => $urls])->render();
        });

        if (! $xml) {
            abort(404);
        }

        return $responseFactory->make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(ResponseFactory $responseFactory): Response
    {
        $settings = WebsiteSetting::current();

        $lines = $settings->search_indexing_enabled
            ? ['User-agent: *', 'Allow: /', 'Disallow: /admin', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return $responseFactory->make(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }
}
