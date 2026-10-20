<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobListingRequest;
use App\Models\Company;
use App\Models\JobListing;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(JobListing::class, 'job_listing');
    }

    public function index(): View
    {
        $listings = JobListing::query()
            ->withCount('applications')
            ->with('company')
            ->when(request('q'), fn ($q) => $q->where('title', 'like', '%'.request('q').'%'))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.job-listings.index', compact('listings'));
    }

    public function create(): View
    {
        return $this->formView(new JobListing);
    }

    public function store(JobListingRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $listing = JobListing::create($data);

        ActivityLogger::log('created', $listing, "Created job listing \"{$listing->title}\"");

        return redirect()->route('admin.job-listings.index')->with('success', 'Job listing created successfully.');
    }

    public function edit(JobListing $jobListing): View
    {
        return $this->formView($jobListing);
    }

    public function update(JobListingRequest $request, JobListing $jobListing): RedirectResponse
    {
        $jobListing->update($this->prepareData($request));
        ActivityLogger::log('updated', $jobListing, "Updated job listing \"{$jobListing->title}\"");

        return redirect()->route('admin.job-listings.index')->with('success', 'Job listing updated successfully.');
    }

    public function destroy(JobListing $jobListing): RedirectResponse
    {
        $jobListing->delete();
        ActivityLogger::log('deleted', null, "Deleted job listing \"{$jobListing->title}\"");

        return redirect()->route('admin.job-listings.index')->with('success', 'Job listing archived successfully.');
    }

    private function formView(JobListing $listing): View
    {
        return view('admin.job-listings.form', [
            'listing' => $listing,
            'companies' => Company::orderBy('name')->get(),
        ]);
    }

    private function prepareData(JobListingRequest $request): array
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            unset($data['slug']);
        }

        return $data;
    }
}
