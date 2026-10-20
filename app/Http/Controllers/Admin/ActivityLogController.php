<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
}
