<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'resume' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'You must consent to the processing of your application.',
            'resume.mimes' => 'The resume must be a PDF file.',
        ];
    }
}
