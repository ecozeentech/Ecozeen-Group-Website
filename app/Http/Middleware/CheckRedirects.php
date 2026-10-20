<?php

namespace App\Http\Middleware;

use App\Models\RedirectRule;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies admin-managed redirect rules (e.g. created automatically when a
 * page's slug changes) before routing proceeds any further. Only exact,
 * active path matches are honoured; nothing here executes arbitrary logic
 * supplied by an end user.
 */
class CheckRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() !== 'GET' || $request->is('admin*')) {
            return $next($request);
        }

        $path = '/'.trim($request->path(), '/');

        // Cache a plain array rather than a Collection: cache.serializable_classes
        // is disabled by default so no PHP object may be unserialized from the
        // cache, which protects against gadget-chain deserialization attacks.
        $redirects = Collection::make(Cache::remember('redirects.active', 300, function () {
            return RedirectRule::where('is_active', true)->pluck('to_path', 'from_path')->all();
        }));

        if ($redirects->has($path)) {
            RedirectRule::where('from_path', $path)->increment('hits');
            $statusCode = RedirectRule::where('from_path', $path)->value('status_code') ?: 301;

            return redirect($redirects->get($path), $statusCode);
        }

        return $next($request);
    }
}
