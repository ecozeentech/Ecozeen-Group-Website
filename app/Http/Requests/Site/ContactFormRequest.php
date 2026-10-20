<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inquiry_type' => ['required', Rule::in([
                'institutional_investment', 'business_partnership', 'enterprise_it_services',
                'commodity_trade', 'media_press', 'executive_careers', 'general',
            ])],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'target_company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'jurisdiction' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'consent' => ['accepted'],
            // Honeypot: real visitors never see or fill this field. Any
            // non-empty value indicates automated spam submission.
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'You must consent to being contacted about your enquiry.',
        ];
    }
}
