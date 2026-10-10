<?php

namespace App\Services\Seo;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ShopCacheInvalidator
{
    private bool $pending = false;

    public function schedule(): void
    {
        if (! config('services.shop_cache.url') || ! config('services.shop_cache.secret')) {
            return;
        }

        // A rolled-back stock or price update must not invalidate the catalog.
        if (DB::transactionLevel() > 0) {
            DB::afterCommit(fn () => $this->pending = true);
        } else {
            $this->pending = true;
        }
    }

    public function flush(): void
    {
        if (! $this->pending) {
            return;
        }
        $this->pending = false;

        try {
            Http::withToken(config('services.shop_cache.secret'))
                ->connectTimeout(2)->timeout(5)
                ->post(config('services.shop_cache.url'))
                ->throw();
        } catch (Throwable $exception) {
            // A frontend outage must never fail an order/payment. The five-minute
            // cache TTL is the fallback; log no credentials or request headers.
            Log::warning('Shop cache invalidation failed', ['exception' => $exception::class]);
        }
    }
}
