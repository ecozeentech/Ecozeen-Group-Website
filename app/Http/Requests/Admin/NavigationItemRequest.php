<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NavigationItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('navigation.manage');
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:navigation_items,id'],
            'page_id' => ['nullable', 'integer', 'exists:pages,id'],
            'label' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
