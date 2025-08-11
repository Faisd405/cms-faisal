<?php

namespace App\Http\Middleware;

use App\Services\Localization\LanguageService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateLanguage
{
    public function __construct(
        protected LanguageService $languageService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only validate for API routes that might have locale parameter
        if ($request->is('api/*') && $request->has('locale')) {
            $request->validate([
                'locale' => 'string|size:2|alpha|lowercase',
            ]);

            $locale = $this->languageService->findByIsoCode($request->get('locale'));

            if (!$locale) {
                // For API routes, return JSON error if locale is invalid
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid locale provided',
                        'error' => [
                            'code' => 'INVALID_LOCALE',
                            'details' => "Locale '{$request->get('locale')}' is not supported"
                        ]
                    ], 400);
                }
            }
        }

        return $next($request);
    }
}
