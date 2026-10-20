<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactInquiryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContactInquiry::class, 'inquiry', ['except' => ['show']]);
    }

    public function index(): View
    {
        $this->authorize('viewAny', ContactInquiry::class);

        $inquiries = ContactInquiry::query()
            ->with('targetCompany', 'handler')
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->when(request('type'), fn ($q) => $q->where('inquiry_type', request('type')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(ContactInquiry $inquiry): View
    {
        $this->authorize('view', $inquiry);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, ContactInquiry $inquiry): RedirectResponse
    {
        $this->authorize('update', $inquiry);

        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'in_progress', 'resolved', 'spam'])],
            'internal_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['handled_by'] = Auth::id();

        $inquiry->update($data);

        return back()->with('success', 'Inquiry updated.');
    }

    public function destroy(ContactInquiry $inquiry): RedirectResponse
    {
        $this->authorize('delete', $inquiry);

        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
