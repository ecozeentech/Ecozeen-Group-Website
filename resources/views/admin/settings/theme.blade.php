<x-layouts.admin title="Theme Settings">
    <x-admin.page-header title="Theme & Design Settings" description="Centralized design tokens rendered site-wide as CSS variables. Only the controlled values below are allowed — no raw CSS or code may be entered." />

    <form method="POST" action="{{ route('admin.settings.theme.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Colors</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ([
                    'primary_color' => 'Primary',
                    'secondary_color' => 'Secondary',
                    'accent_color' => 'Accent',
                    'success_color' => 'Success',
                    'text_color' => 'Text',
                    'muted_text_color' => 'Muted text',
                    'background_color' => 'Background',
                    'surface_color' => 'Surface',
                    'border_color' => 'Border',
                ] as $field => $labelText)
                    <div x-data="{ hex: {{ json_encode(old($field, $theme->$field)) }} }">
                        <label class="form-label">{{ $labelText }}</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="hex" class="h-10 w-12 cursor-pointer rounded-token border border-surface-border">
                            <input type="text" name="{{ $field }}" x-model="hex" maxlength="7" class="form-input flex-1 font-mono">
                        </div>
                        @error($field)
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Typography & Layout</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-admin.select label="Heading font" name="heading_font" :value="$theme->heading_font" :options="array_combine($options['heading_font'], $options['heading_font'])" required />
                <x-admin.select label="Body font" name="body_font" :value="$theme->body_font" :options="array_combine($options['body_font'], $options['body_font'])" required />
                <x-admin.select label="Container width" name="container_width" :value="$theme->container_width" :options="array_combine($options['container_width'], $options['container_width'])" required />
                <x-admin.select label="Section spacing" name="section_spacing" :value="$theme->section_spacing" :options="array_combine($options['section_spacing'], $options['section_spacing'])" required />
                <x-admin.select label="Corner radius" name="radius_scale" :value="$theme->radius_scale" :options="array_combine($options['radius_scale'], $options['radius_scale'])" required />
                <x-admin.select label="Shadow depth" name="shadow_scale" :value="$theme->shadow_scale" :options="array_combine($options['shadow_scale'], $options['shadow_scale'])" required />
            </div>
        </div>

        <div class="card space-y-5 p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Components</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-admin.select label="Header style" name="header_variant" :value="$theme->header_variant" :options="array_combine($options['header_variant'], $options['header_variant'])" required />
                <x-admin.select label="Footer style" name="footer_variant" :value="$theme->footer_variant" :options="array_combine($options['footer_variant'], $options['footer_variant'])" required />
                <x-admin.select label="Button style" name="button_style" :value="$theme->button_style" :options="array_combine($options['button_style'], $options['button_style'])" required />
                <x-admin.select label="Card style" name="card_style" :value="$theme->card_style" :options="array_combine($options['card_style'], $options['card_style'])" required />
            </div>
            <x-admin.checkbox label="Enable dark mode toggle" name="dark_mode_enabled" :checked="$theme->dark_mode_enabled" />
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">Save Theme</button>
        </div>
    </form>
</x-layouts.admin>
