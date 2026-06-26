<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExpireSubscriptionsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->runningUnitTests()) {
            $this->expireSubscriptionsIfNeeded();
        }

        return $next($request);
    }

    private function expireSubscriptionsIfNeeded(): void
    {
        $cacheKey = 'subscriptions_expired_on';

        if (Cache::get($cacheKey) === now()->toDateString()) {
            return;
        }

        try {
            Artisan::call('subscriptions:expire');
            Cache::put($cacheKey, now()->toDateString(), now()->addDay());
        } catch (Throwable) {
            // Avoid breaking page loads if the database is temporarily unavailable.
        }
    }
}
