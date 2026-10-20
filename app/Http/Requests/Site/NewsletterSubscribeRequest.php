<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterSubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'consent' => ['accepted'],
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'consent.accepted' => 'You must consent to receive email communications to subscribe.',
        ];
    }
}
