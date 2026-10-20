<x-layouts.admin title="Website Settings">
    <x-admin.page-header title="Website Settings" description="Global site identity, contact details, and footer content. Edits apply immediately across the live site." />

    <form method="POST" action="{{ route('admin.settings.website.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Identity</h2>
            <x-admin.input label="Site name" name="site_name" :value="$settings->site_name" required />
            <x-admin.input label="Tagline" name="tagline" :value="$settings->tagline" />
            <x-admin.textarea label="Supporting statement" name="supporting_statement" :value="$settings->supporting_statement" rows="2" />
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <x-admin.media-picker label="Logo" field="logo_media_id" :current="$settings->logoMedia" />
                <x-admin.media-picker label="Logo (dark backgrounds)" field="logo_dark_media_id" :current="$settings->logoDarkMedia" />
                <x-admin.media-picker label="Favicon" field="favicon_media_id" :current="$settings->faviconMedia" />
                <x-admin.media-picker label="Default social share image" field="default_og_image_media_id" :current="$settings->defaultOgImageMedia" />
            </div>
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Contact</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-admin.input type="email" label="Public contact email" name="contact_email" :value="$settings->contact_email" />
                <x-admin.input label="Public contact phone" name="contact_phone" :value="$settings->contact_phone" />
                <x-admin.input type="email" label="Inquiry notification email" name="inquiry_email" :value="$settings->inquiry_email" hint="New contact-form inquiries are sent here." />
                <x-admin.input type="email" label="Press/media email" name="press_email" :value="$settings->press_email" />
            </div>
            <x-admin.textarea label="Primary address" name="address_primary" :value="$settings->address_primary" rows="2" />
            <x-admin.textarea label="Secondary address" name="address_secondary" :value="$settings->address_secondary" rows="2" />
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Social Links</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach (['linkedin' => 'LinkedIn', 'twitter' => 'X / Twitter', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube'] as $key => $labelText)
                    <x-admin.input :label="$labelText" :name="'social_links['.$key.']'" :value="$settings->social_links[$key] ?? null" placeholder="https://" />
                @endforeach
            </div>
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Announcement Bar</h2>
            <x-admin.checkbox label="Show announcement bar" name="announcement_enabled" :checked="$settings->announcement_enabled" />
            <x-admin.input label="Announcement text" name="announcement_text" :value="$settings->announcement_text" />
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Footer & Legal</h2>
            <x-admin.textarea label="Footer text" name="footer_text" :value="$settings->footer_text" rows="2" />
            <x-admin.textarea label="Footer legal text" name="footer_legal_text" :value="$settings->footer_legal_text" rows="2" hint="e.g. copyright line." />
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Technical</h2>
            <x-admin.input label="Google Analytics ID" name="google_analytics_id" :value="$settings->google_analytics_id" placeholder="G-XXXXXXXXXX" />
            <x-admin.checkbox label="Newsletter signup enabled" name="newsletter_enabled" :checked="$settings->newsletter_enabled" />
            <x-admin.checkbox label="Sitemap enabled" name="sitemap_enabled" :checked="$settings->sitemap_enabled" />
            <x-admin.checkbox label="Allow search engine indexing" name="search_indexing_enabled" :checked="$settings->search_indexing_enabled" />
            <div class="border-t border-surface-border pt-4">
                <x-admin.checkbox label="Maintenance mode" name="maintenance_mode" :checked="$settings->maintenance_mode" hint="Shows a maintenance page to visitors; admins can still sign in." />
                <div class="mt-3">
                    <x-admin.textarea label="Maintenance message" name="maintenance_message" :value="$settings->maintenance_message" rows="2" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">Save Settings</button>
        </div>
    </form>
</x-layouts.admin>
