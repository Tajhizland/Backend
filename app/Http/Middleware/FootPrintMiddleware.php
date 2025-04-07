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
            // اگر توکن در Authorization header هست
            $token = $request->bearerToken();
            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    Auth::login($accessToken->tokenable);
                }
            }
        }

        $page = $request->path();
        $userIp = $request->ip();
        $userId = auth()->check() ? auth()->id() : null;
        Footprint::create([
            "page" => $page,
            "ip" => $userIp,
            "user_id" => $userId??null,
        ]);
        return $next($request);
    }
}
