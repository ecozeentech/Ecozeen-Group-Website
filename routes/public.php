<?php

use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\CareerController;
use App\Http\Controllers\Site\CompanyController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\PortfolioController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\SeoController;
use App\Http\Controllers\Site\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/insights', [BlogController::class, 'index'])->name('blog.index');
Route::get('/insights/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/insights/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/insights/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{jobListing:slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{jobListing:slug}/apply', [CareerController::class, 'apply'])
    ->middleware('throttle:5,1')
    ->name('careers.apply');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Generic CMS page builder route. Must stay last so it never shadows the
// dedicated directory/listing routes registered above.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');
