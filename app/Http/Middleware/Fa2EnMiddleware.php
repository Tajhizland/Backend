<?php

namespace App\Http\Middleware;

use App\Traits\Fa2En;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Fa2EnMiddleware
{
    use Fa2En;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $convertedRequest = $this->convertFa2En($request->all());
        $request->replace($convertedRequest);
        return $next($request);
    }
}
