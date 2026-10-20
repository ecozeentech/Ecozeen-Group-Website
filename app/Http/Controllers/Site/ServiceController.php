<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->published()
            ->with('category')
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('site.services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === 'published', 404);

        return view('site.services.show', ['service' => $service->load('category', 'companies', 'projects')]);
    }
}
