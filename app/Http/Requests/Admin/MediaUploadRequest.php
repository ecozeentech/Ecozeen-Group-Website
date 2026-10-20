<?php

namespace App\Http\Requests\Admin;

use App\Models\MediaAsset;
use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MediaAsset::class);
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*' => [
                'required',
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,webp,gif,svg,pdf',
            ],
            'collection' => ['nullable', 'string', 'max:100'],
        ];
    }
}
