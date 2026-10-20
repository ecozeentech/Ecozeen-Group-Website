<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

/**
 * Seeds the CMS-managed homepage and a handful of informational/legal
 * pages. All copy is written generically on purpose: no phone numbers,
 * physical addresses, legal registration numbers, financial figures, or
 * customer quotes are fabricated. Administrators can refine this copy
 * through the Page Builder at any time without a deployment.
 */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        $home = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'status' => 'published',
                'published_at' => now(),
                'is_homepage' => true,
                'seo_title' => 'Ecozeen Group — A Diversified Holding Company',
                'seo_description' => 'Ecozeen Group oversees a diversified portfolio of businesses across financial technology, enterprise IT services, leisure, mining, and agriculture.',
            ]
        );

        $this->replaceSections($home, [
            [
                'type' => 'hero',
                'settings' => [
                    'eyebrow' => 'Ecozeen Group',
                    'heading' => 'A diversified group building across fintech, technology, and beyond.',
                    'subheading' => '<p>Ecozeen Group oversees a portfolio of operating businesses spanning financial technology, enterprise IT services, leisure, mining, and agriculture.</p>',
                    'primary_label' => 'Explore Our Companies',
                    'primary_url' => '/companies',
                    'secondary_label' => 'Contact Us',
                    'secondary_url' => '/contact',
                    'alignment' => 'left',
                ],
            ],
            [
                'type' => 'company_collection',
                'settings' => [
                    'heading' => 'Our Companies',
                    'subheading' => 'A look at the operating businesses across the Ecozeen Group portfolio.',
                    'featured_only' => true,
                    'limit' => 6,
                    'cta_label' => 'View all companies',
                    'cta_url' => '/companies',
                ],
            ],
            [
                'type' => 'mission_vision',
                'settings' => [
                    'mission_heading' => 'Our Mission',
                    'mission_text' => '<p>To build and support businesses that create lasting value for the people and markets they serve.</p>',
                    'vision_heading' => 'Our Vision',
                    'vision_text' => '<p>A diversified group recognized for disciplined operations and long-term thinking across every business we own.</p>',
                ],
            ],
            [
                'type' => 'service_collection',
                'settings' => [
                    'heading' => 'Enterprise Services',
                    'subheading' => 'Technology and consulting services delivered by our enterprise IT companies.',
                    'featured_only' => true,
                    'limit' => 3,
                    'cta_label' => 'View all services',
                    'cta_url' => '/services',
                ],
            ],
            [
                'type' => 'cta',
                'settings' => [
                    'heading' => 'Interested in partnering with Ecozeen Group?',
                    'subheading' => 'Reach out to discuss investment, partnership, or enterprise service opportunities.',
                    'primary_label' => 'Get in touch',
                    'primary_url' => '/contact',
                    'style' => 'primary',
                ],
            ],
            [
                'type' => 'newsletter_form',
                'settings' => [
                    'heading' => 'Stay up to date',
                    'subheading' => 'Subscribe for occasional updates from across Ecozeen Group.',
                ],
            ],
        ]);

        $about = Page::query()->updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'About Ecozeen Group',
                'seo_description' => 'Learn about Ecozeen Group, a diversified holding company overseeing operating businesses across multiple sectors.',
            ]
        );

        $this->replaceSections($about, [
            [
                'type' => 'hero',
                'settings' => [
                    'eyebrow' => 'About Us',
                    'heading' => 'About Ecozeen Group',
                    'subheading' => '<p>We are a diversified holding company built around long-term ownership of strong operating businesses.</p>',
                    'alignment' => 'left',
                ],
            ],
            [
                'type' => 'rich_text',
                'settings' => [
                    'heading' => 'Who We Are',
                    'content' => '<p>Ecozeen Group oversees a diversified portfolio of operating businesses across financial technology, enterprise IT services, leisure and entertainment, natural resources, and agriculture. Each operating company is managed independently within its sector while benefiting from the Group\'s shared governance, strategic direction, and investment capability.</p>',
                ],
            ],
            [
                'type' => 'core_values',
                'settings' => [
                    'heading' => 'What Guides Us',
                    'items' => [
                        ['title' => 'Integrity', 'description' => 'We operate with transparency and accountability across every business we own.'],
                        ['title' => 'Long-term thinking', 'description' => 'We build for durability, not short-term results.'],
                        ['title' => 'Operational excellence', 'description' => 'We support our operating companies with the discipline to execute well.'],
                    ],
                ],
            ],
            [
                'type' => 'company_collection',
                'settings' => [
                    'heading' => 'Our Portfolio',
                    'subheading' => 'The operating businesses that make up Ecozeen Group.',
                    'featured_only' => false,
                    'limit' => 12,
                ],
            ],
        ]);

        $this->seedLegalPage(
            'privacy-policy',
            'Privacy Policy',
            $this->privacyPolicyContent()
        );

        $this->seedLegalPage(
            'terms-of-service',
            'Terms of Service',
            $this->termsOfServiceContent()
        );

        $this->seedLegalPage(
            'cookie-policy',
            'Cookie Policy',
            $this->cookiePolicyContent()
        );
    }

    private function seedLegalPage(string $slug, string $title, string $content): void
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => $title.' — Ecozeen Group',
                'seo_description' => "Read Ecozeen Group's {$title}.",
                'no_index' => true,
            ]
        );

        $this->replaceSections($page, [
            [
                'type' => 'rich_text',
                'settings' => [
                    'heading' => $title,
                    'content' => $content,
                    'max_width' => 'narrow',
                ],
            ],
        ]);
    }

    private function replaceSections(Page $page, array $sections): void
    {
        $page->sections()->delete();

        foreach ($sections as $position => $section) {
            PageSection::query()->create([
                'page_id' => $page->id,
                'type' => $section['type'],
                'settings' => $section['settings'],
                'position' => $position,
                'is_visible' => true,
                'visible_desktop' => true,
                'visible_mobile' => true,
            ]);
        }
    }

    private function privacyPolicyContent(): string
    {
        return <<<'HTML'
            <p>This Privacy Policy explains how Ecozeen Group ("we", "us", or "our") collects, uses, and protects information when you use this website.</p>
            <h3>Information We Collect</h3>
            <p>We collect information you provide directly to us, such as when you submit a contact inquiry, subscribe to our newsletter, or apply for a job listing. This may include your name, email address, phone number, and any message or file you choose to submit.</p>
            <h3>How We Use Information</h3>
            <p>We use the information we collect to respond to inquiries, process newsletter subscriptions and job applications, and improve this website.</p>
            <h3>How We Protect Information</h3>
            <p>We take reasonable technical and organizational measures to protect the information you share with us.</p>
            <h3>Your Choices</h3>
            <p>You may unsubscribe from our newsletter at any time using the link included in every email we send. You may also contact us to request information about the data we hold about you.</p>
            <h3>Contact</h3>
            <p>If you have questions about this Privacy Policy, please contact us through our contact form.</p>
            <p><em>This page is managed by Ecozeen Group administrators and may be updated at any time through the content management system.</em></p>
            HTML;
    }

    private function termsOfServiceContent(): string
    {
        return <<<'HTML'
            <p>These Terms of Service govern your use of this website operated by Ecozeen Group.</p>
            <h3>Acceptance of Terms</h3>
            <p>By accessing this website, you agree to be bound by these Terms of Service.</p>
            <h3>Use of the Website</h3>
            <p>You agree to use this website only for lawful purposes and in a manner that does not infringe the rights of, or restrict or inhibit the use and enjoyment of, this website by any third party.</p>
            <h3>Intellectual Property</h3>
            <p>All content on this website, including text, graphics, logos, and images, is the property of Ecozeen Group or its licensors and is protected by applicable intellectual property laws.</p>
            <h3>Changes to These Terms</h3>
            <p>We may update these Terms of Service from time to time. Continued use of this website after changes are posted constitutes acceptance of the updated terms.</p>
            <h3>Contact</h3>
            <p>If you have questions about these Terms of Service, please contact us through our contact form.</p>
            <p><em>This page is managed by Ecozeen Group administrators and may be updated at any time through the content management system.</em></p>
            HTML;
    }

    private function cookiePolicyContent(): string
    {
        return <<<'HTML'
            <p>This Cookie Policy explains how Ecozeen Group uses cookies and similar technologies on this website.</p>
            <h3>What Are Cookies</h3>
            <p>Cookies are small text files placed on your device to help websites function and to collect basic usage information.</p>
            <h3>How We Use Cookies</h3>
            <p>We use cookies to keep the website functioning correctly, such as maintaining your session when you sign in to the admin dashboard.</p>
            <h3>Managing Cookies</h3>
            <p>Most web browsers allow you to control cookies through their settings. Disabling cookies may affect the functionality of this website.</p>
            <p><em>This page is managed by Ecozeen Group administrators and may be updated at any time through the content management system.</em></p>
            HTML;
    }
}
