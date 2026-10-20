<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->published()
            ->with('company', 'category')
            ->orderBy('sort_order')->orderByDesc('completed_at')
            ->paginate(12)
            ->withQueryString();

        return view('site.portfolio.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->status === 'published', 404);

        return view('site.portfolio.show', [
            'project' => $project->load('company', 'category', 'services', 'galleryMedia'),
        ]);
    }
}
