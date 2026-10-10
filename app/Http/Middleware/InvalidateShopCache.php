<?php

namespace App\Http\Middleware;

use App\Services\Seo\ShopCacheInvalidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidateShopCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Also covers bulk admin updates, which bypass Eloquent model events.
        if (! $request->isMethodSafe() && $response->isSuccessful()) {
            app(ShopCacheInvalidator::class)->schedule();
        }

        return $response;
    }
}
