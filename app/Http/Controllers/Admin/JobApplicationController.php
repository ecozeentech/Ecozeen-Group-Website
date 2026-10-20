<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(JobApplication::class, 'job_application', ['except' => ['show']]);
    }

    public function index(): View
    {
        $this->authorize('viewAny', JobApplication::class);

        $applications = JobApplication::query()
            ->with('jobListing')
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.job-applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication): View
    {
        $this->authorize('view', $jobApplication);

        return view('admin.job-applications.show', ['application' => $jobApplication->load('jobListing', 'resume')]);
    }

    /**
     * Resumes are access-restricted: only authorized admin users may
     * download them, streamed through this controller rather than served
     * directly from a public disk URL.
     */
    public function downloadResume(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        abort_unless($jobApplication->resume, 404);

        return Storage::disk($jobApplication->resume->disk)->download(
            $jobApplication->resume->path,
            $jobApplication->resume->original_filename
        );
    }

    public function update(Request $request, JobApplication $jobApplication): RedirectResponse
    {
        $this->authorize('update', $jobApplication);

        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'reviewed', 'shortlisted', 'rejected', 'hired'])],
        ]);

        $jobApplication->update($data);

        return back()->with('success', 'Application status updated.');
    }

    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $this->authorize('delete', $jobApplication);

        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')->with('success', 'Application deleted.');
    }
}
