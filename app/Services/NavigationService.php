<?php

namespace App\Services;

use App\Models\NavigationMenu;

class NavigationService
{
    /**
     * Deliberately not cached via the Cache facade: cache.serializable_classes
     * is disabled by default so no PHP object (including Eloquent models and
     * their relations) may be unserialized from the cache store, which
     * protects against gadget-chain deserialization attacks. A menu with its
     * items is cheap enough to query directly on every request.
     */
    public static function menu(string $location): ?NavigationMenu
    {
        return NavigationMenu::query()
            ->where('location', $location)
            ->with(['items' => function ($query) {
                $query->where('is_visible', true)->with(['page', 'children' => function ($child) {
                    $child->where('is_visible', true)->with('page');
                }]);
            }])
            ->first();
    }

    /**
     * No-op retained for backward compatibility with existing call sites:
     * menu() no longer caches, so there is nothing to invalidate.
     */
    public static function flush(): void
    {
        //
    }
}
