<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    use ApiResponse;
    public function handle(Request $request, Closure $next)
    {
        $user=Auth::user();
        if($user)
        {
            if($user->role=="admin")
            {
                return $next($request);
            }
        }
        return  $this->forbiddenResponse([],"شما دسترسی لازم را ندارید");
    }
}
