<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $this->authorize('activity.view');

        $logs = ActivityLog::query()
            ->with('user')
            ->when(request('action'), fn ($q) => $q->where('action', request('action')))
            ->latest('created_at')
            ->paginate(40)
            ->withQueryString();

        return view('admin.activity.index', compact('logs'));
    }

    public function destroy(ActivityLog $activity): RedirectResponse
    {
        $this->authorizeClear();

        $activity->delete();

        return back()->with('success', 'Activity entry deleted.');
    }

    public function clear(): RedirectResponse
    {
        $this->authorizeClear();

        ActivityLog::query()->delete();

        return redirect()->route('admin.activity.index')->with('success', 'Activity log cleared.');
    }

    private function authorizeClear(): void
    {
        $this->authorize('activity.view');

        abort_unless(
            auth()->user()?->hasRole(['Super Administrator', 'Administrator']),
            403
        );
    }
}
