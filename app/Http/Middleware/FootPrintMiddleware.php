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
        $user=Auth::user();
        Footprint::create([
            "page" => $page,
            "ip" => $userIp,
            "user_id" => $user->id??null,
        ]);
        return $next($request);
    }
}
