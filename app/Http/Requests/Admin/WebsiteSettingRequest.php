<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('settings.manage');
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'supporting_statement' => ['nullable', 'string', 'max:500'],
            'logo_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'logo_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'logo_dark_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'logo_dark_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'favicon_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'favicon_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'default_og_image_media_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'default_og_image_media_id_upload' => ['nullable', 'image', 'max:8192'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'inquiry_email' => ['nullable', 'email', 'max:255'],
            'press_email' => ['nullable', 'email', 'max:255'],
            'address_primary' => ['nullable', 'string', 'max:1000'],
            'address_secondary' => ['nullable', 'string', 'max:1000'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'url', 'max:255'],
            'announcement_text' => ['nullable', 'string', 'max:255'],
            'announcement_enabled' => ['nullable', 'boolean'],
            'footer_text' => ['nullable', 'string', 'max:500'],
            'footer_legal_text' => ['nullable', 'string', 'max:500'],
            'google_analytics_id' => ['nullable', 'string', 'max:32'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'newsletter_enabled' => ['nullable', 'boolean'],
            'sitemap_enabled' => ['nullable', 'boolean'],
            'search_indexing_enabled' => ['nullable', 'boolean'],
        ];
    }
}
