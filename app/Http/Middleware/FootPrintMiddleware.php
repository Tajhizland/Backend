<?php

namespace App\Http\Middleware;

use App\Models\Footprint;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class FootPrintMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            $token = $request->bearerToken();

            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);

                if ($accessToken && $accessToken->tokenable) {
                    Auth::login($accessToken->tokenable);
                }
            }
        }

        // حالا auth()->id() باید مقدار داشته باشه اگر لاگین بوده
        $page = $request->path();
        $userIp = $request->ip();

        Footprint::create([
            'page' => $page,
            'ip' => $userIp,
            'user_id' => auth()->id(), // ← اگه لاگین نباشه، null
        ]);
        return $next($request);
    }
}
