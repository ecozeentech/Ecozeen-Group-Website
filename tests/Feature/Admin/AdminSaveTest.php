<?php

namespace Tests\Feature\Admin;

use App\Models\Company;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Product;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Models\WebsiteSetting;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSaveTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Administrator');
    }

    public function test_updating_a_product_without_media_fields_keeps_existing_images(): void
    {
        $logo = MediaAsset::factory()->create();
        $product = Product::factory()->create([
            'logo_media_id' => $logo->id,
            'features' => ['Existing feature'],
        ]);

        $this->actingAs($this->admin)
            ->put("/admin/products/{$product->id}", [
                'name' => 'Updated Product',
                'status' => 'published',
            ])
            ->assertRedirect();

        $product->refresh();
        $this->assertSame($logo->id, $product->logo_media_id);
        $this->assertSame('Updated Product', $product->name);
    }

    public function test_product_features_raw_is_parsed_on_save(): void
    {
        $product = Product::factory()->create(['features' => ['Old']]);

        $this->actingAs($this->admin)
            ->put("/admin/products/{$product->id}", [
                'name' => $product->name,
                'status' => 'published',
                'features_raw' => "Ledger automation\nTax compliance\n",
            ])
            ->assertRedirect();

        $this->assertSame(
            ['Ledger automation', 'Tax compliance'],
            $product->fresh()->features
        );
    }

    public function test_website_settings_accept_placeholder_social_urls(): void
    {
        WebsiteSetting::current();

        $this->actingAs($this->admin)
            ->put('/admin/settings/website', [
                'site_name' => 'Ecozeen Group',
                'social_links' => [
                    'linkedin' => 'https://',
                    'x' => '',
                    'youtube' => 'https://youtube.com/ecozeen',
                ],
            ])
            ->assertRedirect(route('admin.settings.website.edit'))
            ->assertSessionHasNoErrors();

        $settings = WebsiteSetting::current()->fresh();
        $this->assertSame(['youtube' => 'https://youtube.com/ecozeen'], $settings->social_links);
    }

    public function test_website_settings_form_exposes_header_and_footer_editors(): void
    {
        WebsiteSetting::current();

        $this->actingAs($this->admin)
            ->get('/admin/settings/website')
            ->assertOk()
            ->assertSee('Header & navigation')
            ->assertSee('Primary button label')
            ->assertSee('Extra legal note');
    }

    public function test_website_settings_save_header_call_to_action_buttons(): void
    {
        WebsiteSetting::current();

        $this->actingAs($this->admin)
            ->put('/admin/settings/website', [
                'site_name' => 'Ecozeen Group',
                'header_cta_label' => 'Start a mandate',
                'header_cta_url' => '/partner',
                'header_secondary_label' => 'Talk to us',
                'header_secondary_url' => '/contact',
            ])
            ->assertRedirect(route('admin.settings.website.edit'))
            ->assertSessionHasNoErrors();

        $settings = WebsiteSetting::current()->fresh();
        $this->assertSame('Start a mandate', $settings->header_cta_label);
        $this->assertSame('/partner', $settings->header_cta_url);
        $this->assertSame('Talk to us', $settings->header_secondary_label);
        $this->assertSame('/contact', $settings->header_secondary_url);
    }

    public function test_company_placeholder_website_url_does_not_fail_validation(): void
    {
        $company = Company::factory()->create();

        $this->actingAs($this->admin)
            ->put("/admin/companies/{$company->id}", [
                'name' => $company->name,
                'type' => 'subsidiary',
                'status' => 'published',
                'website_url' => 'https://',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertNull($company->fresh()->website_url);
    }

    public function test_theme_settings_accept_hex_without_hash(): void
    {
        $theme = ThemeSetting::current();

        $this->actingAs($this->admin)
            ->put('/admin/settings/theme', [
                'primary_color' => '0F172A',
                'secondary_color' => '#2563EB',
                'accent_color' => '#38BDF8',
                'success_color' => '#10B981',
                'text_color' => '#0F172A',
                'muted_text_color' => '#64748B',
                'background_color' => '#F8FAFC',
                'surface_color' => '#FFFFFF',
                'border_color' => '#E2E8F0',
                'heading_font' => 'Manrope',
                'body_font' => 'Inter',
                'container_width' => 'default',
                'section_spacing' => 'default',
                'radius_scale' => 'soft',
                'shadow_scale' => 'subtle',
                'header_variant' => 'glass',
                'footer_variant' => 'expanded',
                'button_style' => 'solid',
                'card_style' => 'bordered',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertSame('#0F172A', $theme->fresh()->primary_color);
        $this->assertSame('#10B981', $theme->fresh()->success_color);
    }

    public function test_page_builder_rejects_invalid_section_json(): void
    {
        $page = Page::factory()->create();
        $section = $page->sections()->create([
            'type' => 'feature_grid',
            'settings' => ['items' => [['title' => 'Keep me']]],
            'position' => 0,
        ]);

        $this->actingAs($this->admin)
            ->from("/admin/pages/{$page->id}/edit")
            ->put("/admin/pages/{$page->id}/sections/{$section->id}", [
                'settings' => ['items' => '{not-json'],
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('settings.items');

        $this->assertSame('Keep me', $section->fresh()->settings['items'][0]['title']);
    }

    public function test_explicit_media_remove_clears_the_image(): void
    {
        $logo = MediaAsset::factory()->create();
        $product = Product::factory()->create(['logo_media_id' => $logo->id]);

        $this->actingAs($this->admin)
            ->put("/admin/products/{$product->id}", [
                'name' => $product->name,
                'status' => 'published',
                'logo_media_id' => '',
            ])
            ->assertRedirect();

        $this->assertNull($product->fresh()->logo_media_id);
    }
}
