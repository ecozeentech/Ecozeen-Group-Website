<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsiteSettingRequest;
use App\Models\NavigationMenu;
use App\Models\WebsiteSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    use HandlesMediaUploads;

    public function edit(): View
    {
        $this->authorize('settings.manage');

        return view('admin.settings.website', [
            'settings' => WebsiteSetting::current(),
            'mainMenu' => NavigationMenu::query()->where('location', 'main')->first(),
            'footerMenu' => NavigationMenu::query()->where('location', 'footer')->first(),
        ]);
    }

    public function update(WebsiteSettingRequest $request): RedirectResponse
    {
        $this->authorize('settings.manage');

        $settings = WebsiteSetting::current();
        $data = $request->validated();

        unset(
            $data['logo_media_id_upload'], $data['logo_dark_media_id_upload'],
            $data['favicon_media_id_upload'], $data['default_og_image_media_id_upload']
        );

        $data['logo_media_id'] = $this->resolveMediaId($request, 'logo_media_id', 'branding', $settings->logo_media_id);
        $data['logo_dark_media_id'] = $this->resolveMediaId($request, 'logo_dark_media_id', 'branding', $settings->logo_dark_media_id);
        $data['favicon_media_id'] = $this->resolveMediaId($request, 'favicon_media_id', 'branding', $settings->favicon_media_id);
        $data['default_og_image_media_id'] = $this->resolveMediaId($request, 'default_og_image_media_id', 'branding', $settings->default_og_image_media_id);
        $data['social_links'] = array_filter($data['social_links'] ?? []);
        $data['announcement_enabled'] = $request->boolean('announcement_enabled');
        $data['maintenance_mode'] = $request->boolean('maintenance_mode');
        $data['newsletter_enabled'] = $request->boolean('newsletter_enabled');
        $data['sitemap_enabled'] = $request->boolean('sitemap_enabled');
        $data['search_indexing_enabled'] = $request->boolean('search_indexing_enabled');
        $data['updated_by'] = Auth::id();

        $settings->update($data);

        ActivityLogger::log('updated', $settings, 'Updated website settings');

        return redirect()->route('admin.settings.website.edit')->with('success', 'Website settings saved.');
    }
}
