<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactInquiryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GlobalSectionController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\NavigationMenuController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ThemeSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('pages', PageController::class)->except(['show']);
    Route::post('pages/{page}/duplicate', [PageController::class, 'duplicate'])->name('pages.duplicate');
    Route::post('pages/{page}/publish', [PageController::class, 'publish'])->name('pages.publish');
    Route::post('pages/{page}/unpublish', [PageController::class, 'unpublish'])->name('pages.unpublish');
    Route::post('pages/{page}/restore-revision/{revision}', [PageController::class, 'restoreRevision'])->name('pages.restore-revision');
    Route::post('pages/{page}/sections', [PageSectionController::class, 'store'])->name('pages.sections.store');
    Route::put('pages/{page}/sections/{section}', [PageSectionController::class, 'update'])->name('pages.sections.update');
    Route::delete('pages/{page}/sections/{section}', [PageSectionController::class, 'destroy'])->name('pages.sections.destroy');
    Route::post('pages/{page}/sections/reorder', [PageSectionController::class, 'reorder'])->name('pages.sections.reorder');

    Route::resource('global-sections', GlobalSectionController::class)->except(['show']);

    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('product-categories', ProductCategoryController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('project-categories', ProjectCategoryController::class)->except(['show']);

    Route::resource('blog-posts', BlogPostController::class)->except(['show']);
    Route::post('blog-posts/{blog_post}/publish', [BlogPostController::class, 'publish'])->name('blog-posts.publish');
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
    Route::resource('blog-tags', BlogTagController::class)->except(['show']);

    Route::resource('media', MediaAssetController::class)->except(['create', 'show', 'edit']);

    Route::resource('navigation', NavigationMenuController::class)->except(['show']);
    Route::post('navigation/{navigation}/items', [NavigationMenuController::class, 'storeItem'])->name('navigation.items.store');
    Route::put('navigation/{navigation}/items/{item}', [NavigationMenuController::class, 'updateItem'])->name('navigation.items.update');
    Route::delete('navigation/{navigation}/items/{item}', [NavigationMenuController::class, 'destroyItem'])->name('navigation.items.destroy');
    Route::post('navigation/{navigation}/items/reorder', [NavigationMenuController::class, 'reorderItems'])->name('navigation.items.reorder');

    Route::resource('job-listings', JobListingController::class)->except(['show']);
    Route::resource('job-applications', JobApplicationController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('job-applications/{job_application}/resume', [JobApplicationController::class, 'downloadResume'])->name('job-applications.resume');

    Route::resource('inquiries', ContactInquiryController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('newsletter', NewsletterSubscriberController::class)->only(['index', 'update', 'destroy']);

    Route::resource('redirects', RedirectController::class)->except(['show']);

    Route::get('settings/website', [WebsiteSettingController::class, 'edit'])->name('settings.website.edit');
    Route::put('settings/website', [WebsiteSettingController::class, 'update'])->name('settings.website.update');
    Route::get('settings/theme', [ThemeSettingController::class, 'edit'])->name('settings.theme.edit');
    Route::put('settings/theme', [ThemeSettingController::class, 'update'])->name('settings.theme.update');

    Route::resource('users', UserController::class)->except(['show']);
    Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');

    Route::resource('roles', RoleController::class)->except(['show']);

    Route::get('activity', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::delete('activity/clear', [ActivityLogController::class, 'clear'])->name('activity.clear');
    Route::delete('activity/{activity}', [ActivityLogController::class, 'destroy'])->name('activity.destroy');
});
