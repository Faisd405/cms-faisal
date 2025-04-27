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
    )
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->validate([
            'locale' => 'sometimes|string',
        ]);

        if ($request->has('locale')) {
            $getLocale = $this->languageService->findByIsoCode($request->get('locale'));
        }

        if (empty($getLocale)) {
            $getLocale = $this->languageService->findByDefault();
        }

        app()->setLocale($getLocale->iso_code);

        return $next($request);
    }
}
