<?php

namespace App\Http\Requests\Admin\Concerns;

trait NormalizesOptionalUrls
{
    /**
     * Drop empty or scheme-only URL placeholders so the `url` rule
     * does not reject a normal "save without filling this field" submit.
     *
     * @param  list<string>  $keys
     */
    protected function blankPlaceholderUrls(array $keys): void
    {
        $merge = [];

        foreach ($keys as $key) {
            $value = $this->input($key);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if ($value === '' || $value === 'https://' || $value === 'http://') {
                $merge[$key] = null;
            }
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }
}
