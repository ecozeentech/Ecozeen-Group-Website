<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\ContactInquiry;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\MediaAsset;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

/**
 * Every figure rendered here is a direct, real-time count or query against
 * the production database tables -- there is no invented/mocked analytics
 * data anywhere in this dashboard.
 */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'companies' => Company::count(),
            'products' => Product::count(),
            'services' => Service::count(),
            'projects' => Project::count(),
            'pages' => Page::count(),
            'pages_published' => Page::where('status', 'published')->count(),
            'blog_posts' => BlogPost::count(),
            'blog_posts_published' => BlogPost::where('status', 'published')->count(),
            'media_assets' => MediaAsset::count(),
            'open_job_listings' => JobListing::where('status', 'open')->count(),
            'new_job_applications' => JobApplication::where('status', 'new')->count(),
            'new_inquiries' => ContactInquiry::where('status', 'new')->count(),
            'newsletter_subscribers' => NewsletterSubscriber::where('status', 'subscribed')->count(),
        ];

        $recentInquiries = ContactInquiry::query()->latest()->limit(5)->get();
        $recentActivity = ActivityLog::query()->with('user')->latest('created_at')->limit(8)->get();
        $recentPages = Page::query()->latest('updated_at')->limit(6)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'recentActivity' => $recentActivity,
            'recentPages' => $recentPages,
        ]);
    }
}
