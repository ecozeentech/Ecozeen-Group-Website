<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\JobApplicationRequest;
use App\Mail\JobApplicationConfirmationMail;
use App\Mail\JobApplicationReceivedMail;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\WebsiteSetting;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $listings = JobListing::query()->open()->with('company')->orderByDesc('created_at')->paginate(10);

        return view('site.careers.index', compact('listings'));
    }

    public function show(JobListing $jobListing): View
    {
        abort_unless(in_array($jobListing->status, ['open', 'closed'], true), 404);

        return view('site.careers.show', ['listing' => $jobListing->load('company')]);
    }

    public function apply(JobApplicationRequest $request, JobListing $jobListing, MediaUploadService $uploader): RedirectResponse
    {
        abort_unless($jobListing->status === 'open', 404);

        $data = $request->validated();

        // Resumes are stored on the private disk and are never publicly
        // accessible; only authorized admin users can retrieve them via
        // the access-restricted admin download route.
        $resume = $uploader->store($request->file('resume'), config('ecozeen.media.private_disk'), 'resumes');

        $application = JobApplication::create([
            'job_listing_id' => $jobListing->id,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_media_id' => $resume->id,
            'ip_address' => $request->ip(),
        ]);

        $settings = WebsiteSetting::current();
        $recipient = $settings->inquiry_email ?: $settings->contact_email;

        if ($recipient) {
            $this->sendMailSafely(fn () => Mail::to($recipient)->send(new JobApplicationReceivedMail($application)));
        }

        $this->sendMailSafely(fn () => Mail::to($application->email)->send(new JobApplicationConfirmationMail($application)));

        return back()->with('status', 'Your application has been received. Thank you for your interest in Ecozeen Group.');
    }

    private function sendMailSafely(callable $send): void
    {
        try {
            $send();
        } catch (\Throwable $e) {
            Log::error('Failed to send application-related email: '.$e->getMessage());
        }
    }
}
