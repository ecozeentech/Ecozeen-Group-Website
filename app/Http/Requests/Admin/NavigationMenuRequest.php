<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NavigationMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('navigation.manage');
    }

    public function rules(): array
    {
        $id = $this->route('navigation')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'alpha_dash', 'max:255', Rule::unique('navigation_menus', 'location')->ignore($id)],
        ];
    }
}
