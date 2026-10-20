<?php

namespace App\Http\Requests\Admin;

use App\Models\ThemeSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('theme.manage');
    }

    protected function prepareForValidation(): void
    {
        $colors = [
            'primary_color', 'secondary_color', 'accent_color', 'success_color',
            'text_color', 'muted_text_color', 'background_color', 'surface_color', 'border_color',
        ];

        $merge = [];
        foreach ($colors as $key) {
            $value = $this->input($key);
            if (is_string($value) && preg_match('/^[0-9A-Fa-f]{6}$/', trim($value))) {
                $merge[$key] = '#'.trim($value);
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    /**
     * Color fields accept only strict 6-digit hex values and every
     * select-style field is restricted to ThemeSetting::allowedOptions(),
     * so administrators cannot persist arbitrary/unsafe CSS values.
     */
    public function rules(): array
    {
        $hex = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        $options = ThemeSetting::allowedOptions();

        return [
            'primary_color' => $hex,
            'secondary_color' => $hex,
            'accent_color' => $hex,
            'success_color' => $hex,
            'text_color' => $hex,
            'muted_text_color' => $hex,
            'background_color' => $hex,
            'surface_color' => $hex,
            'border_color' => $hex,
            'heading_font' => ['required', Rule::in($options['heading_font'])],
            'body_font' => ['required', Rule::in($options['body_font'])],
            'container_width' => ['required', Rule::in($options['container_width'])],
            'section_spacing' => ['required', Rule::in($options['section_spacing'])],
            'radius_scale' => ['required', Rule::in($options['radius_scale'])],
            'shadow_scale' => ['required', Rule::in($options['shadow_scale'])],
            'header_variant' => ['required', Rule::in($options['header_variant'])],
            'footer_variant' => ['required', Rule::in($options['footer_variant'])],
            'button_style' => ['required', Rule::in($options['button_style'])],
            'card_style' => ['required', Rule::in($options['card_style'])],
            'dark_mode_enabled' => ['nullable', 'boolean'],
        ];
    }
}
