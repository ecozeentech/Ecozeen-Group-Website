<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ThemeSettingRequest;
use App\Models\ThemeSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ThemeSettingController extends Controller
{
    public function edit(): View
    {
        $this->authorize('theme.manage');

        return view('admin.settings.theme', [
            'theme' => ThemeSetting::current(),
            'options' => ThemeSetting::allowedOptions(),
        ]);
    }

    public function update(ThemeSettingRequest $request): RedirectResponse
    {
        $this->authorize('theme.manage');

        $theme = ThemeSetting::current();
        $data = $request->validated();
        $data['dark_mode_enabled'] = $request->boolean('dark_mode_enabled');
        $data['updated_by'] = Auth::id();

        $theme->update($data);

        ActivityLogger::log('updated', $theme, 'Updated theme & design settings');

        return redirect()->route('admin.settings.theme.edit')->with('success', 'Theme settings saved. Changes apply across the site immediately.');
    }
}
