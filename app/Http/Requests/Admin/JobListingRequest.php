<?php

namespace App\Http\Requests\Admin;

use App\Models\JobListing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $listing = $this->route('job_listing') ?? new JobListing;

        return $this->isMethod('POST')
            ? $this->user()->can('create', JobListing::class)
            : $this->user()->can('update', $listing);
    }

    public function rules(): array
    {
        $listingId = $this->route('job_listing')?->id;

        return [
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('job_listings', 'slug')->ignore($listingId)],
            'department' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'contract', 'internship'])],
            'description' => ['required', 'string', 'max:20000'],
            'requirements' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', Rule::in(['draft', 'open', 'closed'])],
            'closes_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ];
    }
}
