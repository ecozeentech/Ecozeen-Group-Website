<?php

namespace App\Models\Concerns;

trait HasSeoFields
{
    public function seoTitle(): string
    {
        return $this->seo_title ?: (string) ($this->name ?? $this->title ?? '');
    }

    public function seoDescription(): string
    {
        return $this->seo_description ?: (string) ($this->short_description ?? $this->summary ?? $this->excerpt ?? '');
    }

    public function seoImage(): mixed
    {
        return $this->seoImageMedia ?? $this->coverMedia ?? $this->logoMedia ?? null;
    }
}
