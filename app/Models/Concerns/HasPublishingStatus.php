<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasPublishingStatus
{
    /**
     * Not every model using this trait tracks a publish date (e.g.
     * Product/Service/Project are simply draft/published/archived, while
     * Page and BlogPost also support scheduled publishing). We detect
     * support for the scheduling fields via the model's `$fillable` list
     * rather than requiring every consumer to have those columns.
     */
    public function scopePublished(Builder $query): Builder
    {
        $query->where('status', 'published');

        if ($this->hasScheduledPublishing()) {
            $query->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
        }

        return $query;
    }

    public function isPubliclyVisible(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->hasScheduledPublishing() && $this->published_at && Carbon::parse($this->published_at)->isFuture()) {
            return false;
        }

        return true;
    }

    private function hasScheduledPublishing(): bool
    {
        return in_array('published_at', $this->getFillable(), true);
    }
}
