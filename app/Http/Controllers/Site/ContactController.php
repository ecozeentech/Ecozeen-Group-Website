<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContactFormRequest;
use App\Mail\ContactInquiryReceivedMail;
use App\Models\Company;
use App\Models\ContactInquiry;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('site.contact', [
            'companies' => Company::published()->orderBy('name')->get(),
        ]);
    }

    public function store(ContactFormRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['website']);
        $data['consent_at'] = now();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = (string) $request->userAgent();

        $inquiry = ContactInquiry::create($data);

        $settings = WebsiteSetting::current();
        $recipient = $settings->inquiry_email ?: $settings->contact_email;

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new ContactInquiryReceivedMail($inquiry));
            } catch (\Throwable $e) {
                Log::error('Failed to send contact inquiry notification email: '.$e->getMessage());
            }
        }

        return back()->with('status', 'Thank you. Your enquiry has been received and our team will respond shortly.');
    }
}
