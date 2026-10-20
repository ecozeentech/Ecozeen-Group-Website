<?php

namespace Tests\Feature\Site;

use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Page;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_renders_the_published_homepage(): void
    {
        Page::factory()->homepage()->published()->create(['title' => 'Welcome to Ecozeen Group']);

        $this->get('/')->assertOk()->assertSee('Welcome to Ecozeen Group');
    }

    public function test_the_homepage_returns_a_fallback_when_no_homepage_is_configured(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_the_company_directory_lists_only_published_companies(): void
    {
        $published = Company::factory()->create(['name' => 'Published Subsidiary']);
        $draft = Company::factory()->draft()->create(['name' => 'Draft Subsidiary']);

        $response = $this->get('/companies')->assertOk();
        $response->assertSee('Published Subsidiary');
        $response->assertDontSee('Draft Subsidiary');
    }

    public function test_a_company_detail_page_is_reachable_by_slug(): void
    {
        $company = Company::factory()->create(['name' => 'Ecozeen Swap']);

        $this->get("/companies/{$company->slug}")->assertOk()->assertSee('Ecozeen Swap');
    }

    public function test_an_unpublished_company_detail_page_404s(): void
    {
        $company = Company::factory()->draft()->create();

        $this->get("/companies/{$company->slug}")->assertNotFound();
    }

    public function test_the_product_directory_and_detail_page_are_reachable(): void
    {
        $product = Product::factory()->create(['name' => 'Sample Product']);

        $this->get('/products')->assertOk();
        $this->get("/products/{$product->slug}")->assertOk()->assertSee('Sample Product');
    }

    public function test_the_service_directory_and_detail_page_are_reachable(): void
    {
        $service = Service::factory()->create(['name' => 'Sample Service']);

        $this->get('/services')->assertOk();
        $this->get("/services/{$service->slug}")->assertOk()->assertSee('Sample Service');
    }

    public function test_the_portfolio_directory_and_detail_page_are_reachable(): void
    {
        $project = Project::factory()->create(['title' => 'Sample Project']);

        $this->get('/portfolio')->assertOk();
        $this->get("/portfolio/{$project->slug}")->assertOk()->assertSee('Sample Project');
    }

    public function test_the_blog_index_and_a_published_post_are_reachable(): void
    {
        $post = BlogPost::factory()->published()->create(['title' => 'Sample Insight']);

        $this->get('/insights')->assertOk();
        $this->get("/insights/{$post->slug}")->assertOk()->assertSee('Sample Insight');
    }

    public function test_an_unpublished_blog_post_404s(): void
    {
        $post = BlogPost::factory()->create(['status' => 'draft']);

        $this->get("/insights/{$post->slug}")->assertNotFound();
    }

    public function test_a_generic_cms_page_is_reachable_by_slug(): void
    {
        $page = Page::factory()->published()->create(['title' => 'About Ecozeen Group']);

        $this->get("/{$page->slug}")->assertOk()->assertSee('About Ecozeen Group');
    }

    public function test_a_draft_cms_page_404s(): void
    {
        $page = Page::factory()->create(['status' => 'draft']);

        $this->get("/{$page->slug}")->assertNotFound();
    }

    public function test_an_unknown_route_returns_a_404_response(): void
    {
        $this->get('/this-page-definitely-does-not-exist')->assertNotFound();
    }

    public function test_the_sitemap_and_robots_routes_respond_successfully(): void
    {
        $this->get('/sitemap.xml')->assertOk();
        $this->get('/robots.txt')->assertOk();
    }
}
