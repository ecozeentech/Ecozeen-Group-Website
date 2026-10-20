<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedirectRule;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(): View
    {
        $this->authorize('redirects.manage');

        $redirects = RedirectRule::orderByDesc('created_at')->paginate(30);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create(): View
    {
        $this->authorize('redirects.manage');

        return view('admin.redirects.form', ['redirect' => new RedirectRule]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('redirects.manage');

        $redirect = RedirectRule::create($this->validated($request));
        ActivityLogger::log('created', $redirect, "Created redirect {$redirect->from_path} -> {$redirect->to_path}");
        Cache::forget('redirects.active');

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect created.');
    }

    public function edit(RedirectRule $redirect): View
    {
        $this->authorize('redirects.manage');

        return view('admin.redirects.form', compact('redirect'));
    }

    public function update(Request $request, RedirectRule $redirect): RedirectResponse
    {
        $this->authorize('redirects.manage');

        $redirect->update($this->validated($request, $redirect));
        ActivityLogger::log('updated', $redirect, "Updated redirect {$redirect->from_path} -> {$redirect->to_path}");
        Cache::forget('redirects.active');

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect updated.');
    }

    public function destroy(RedirectRule $redirect): RedirectResponse
    {
        $this->authorize('redirects.manage');

        $redirect->delete();
        ActivityLogger::log('deleted', null, "Deleted redirect {$redirect->from_path}");
        Cache::forget('redirects.active');

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect deleted.');
    }

    private function validated(Request $request, ?RedirectRule $redirect = null): array
    {
        $data = $request->validate([
            'from_path' => ['required', 'string', 'max:255', Rule::unique('redirects', 'from_path')->ignore($redirect?->id)],
            'to_path' => ['required', 'string', 'max:255'],
            'status_code' => ['required', Rule::in([301, 302])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['from_path'] = '/'.ltrim($data['from_path'], '/');
        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
