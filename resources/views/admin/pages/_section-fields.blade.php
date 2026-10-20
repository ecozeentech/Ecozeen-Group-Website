{{--
    Renders one input per declared field for a section type, driven
    entirely by SectionTypeRegistry::definitions() so the admin UI can
    never submit a field that the server-side sanitizer doesn't expect.
    "list" fields use a JSON textarea (decoded server-side) to keep this
    generic across every section type without a bespoke UI per type.
--}}
<div class="space-y-4">
    @foreach ($fields as $field => $fieldType)
        @php($value = $settings[$field] ?? null)
        @php($fieldId = $type.'-'.$field)
        <div>
            <label for="{{ $fieldId }}" class="form-label">{{ \Illuminate\Support\Str::headline($field) }}</label>

            @switch($fieldType)
                @case('richtext')
                    <textarea id="{{ $fieldId }}" name="settings[{{ $field }}]" rows="4" class="form-textarea">{{ $value }}</textarea>
                    <p class="form-hint">Basic formatting only (bold, links, lists). Scripts and unsafe markup are stripped automatically.</p>
                    @break

                @case('bool')
                    <div class="mt-1">
                        <input type="checkbox" id="{{ $fieldId }}" name="settings[{{ $field }}]" value="1" @checked($value) class="rounded border-slate-300 text-brand-blue">
                    </div>
                    @break

                @case('int')
                    <input type="number" id="{{ $fieldId }}" name="settings[{{ $field }}]" value="{{ $value }}" class="form-input">
                    @break

                @case('url')
                    <input type="text" id="{{ $fieldId }}" name="settings[{{ $field }}]" value="{{ $value }}" placeholder="/path or https://..." class="form-input">
                    @break

                @case('list')
                    <textarea id="{{ $fieldId }}" name="settings[{{ $field }}]" rows="5" class="form-textarea font-mono text-xs">{{ json_encode($value ?? [], JSON_PRETTY_PRINT) }}</textarea>
                    <p class="form-hint">JSON array. Example structures: feature/value items <code>[{"title":"...","description":"..."}]</code>, FAQ <code>[{"question":"...","answer":"..."}]</code>, stats <code>[{"value":"...","label":"..."}]</code>, testimonials <code>[{"quote":"...","name":"...","role":"..."}]</code>, gallery/logo media <code>[12, 34]</code> (media IDs from the library).</p>
                    @break

                @default
                    <input type="text" id="{{ $fieldId }}" name="settings[{{ $field }}]" value="{{ $value }}" class="form-input">
            @endswitch
        </div>
    @endforeach
</div>
