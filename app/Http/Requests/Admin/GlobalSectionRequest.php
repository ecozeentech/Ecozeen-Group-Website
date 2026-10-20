<?php

namespace App\Http\Requests\Admin;

use App\Support\PageBuilder\SectionTypeRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GlobalSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('pages.update');
    }

    public function rules(): array
    {
        $id = $this->route('global_section')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'identifier' => ['required', 'alpha_dash', 'max:255', Rule::unique('global_sections', 'identifier')->ignore($id)],
            'type' => ['required', Rule::in(SectionTypeRegistry::types())],
        ];
    }
}
