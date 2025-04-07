<?php

namespace App\Http\Middleware;

use App\Models\Footprint;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FootPrintMiddleware
{
    public function handle(Request $request, Closure $next)
    {
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
