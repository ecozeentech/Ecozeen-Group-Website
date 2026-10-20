<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('user') ?? new User;

        return $this->isMethod('POST')
            ? $this->user()->can('create', User::class)
            : $this->user()->can('update', $target);
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'is_active' => ['nullable', 'boolean'],
        ];

        // Password is required when creating a user, optional on update.
        $rules['password'] = $this->isMethod('POST')
            ? ['required', 'string', Password::min(12)]
            : ['nullable', 'string', Password::min(12)];

        return $rules;
    }
}
