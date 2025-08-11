<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set content type to JSON for API routes
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // Add CORS headers
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With');
        $response->headers->set('Access-Control-Max-Age', '86400');

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Add rate limit headers if available
        if ($request->route() && $request->route()->middleware()) {
            foreach ($request->route()->middleware() as $middleware) {
                if (str_starts_with($middleware, 'throttle:')) {
                    $response->headers->set('X-RateLimit-Limit', '200');
                    break;
                }
            }
        }

        return $response;
    }
}
