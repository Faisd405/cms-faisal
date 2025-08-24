<?php

namespace App\Http\Middleware;

use App\Models\Datamaster\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get language from session or default
        $languageCode = Session::get('current_language');
        
        // If no language in session, try to detect from browser or use default
        if (!$languageCode) {
            $languageCode = $this->detectLanguageFromRequest($request);
        }
        
        // Validate language exists and is active
        $language = Language::where('code', $languageCode)
            ->where('is_active', true)
            ->first();
            
        if (!$language) {
            // Fallback to default language
            $language = Language::where('is_default', true)
                ->where('is_active', true)
                ->first();
                
            if (!$language) {
                // If no default language, get first active language
                $language = Language::where('is_active', true)->first();
            }
        }
        
        if ($language) {
            Session::put('current_language', $language->code);
            App::setLocale($language->code);
            
            // Share current language with all views
            view()->share('currentLanguage', $language);
            view()->share('availableLanguages', Language::where('is_active', true)->get());
        }

        return $next($request);
    }
    
    private function detectLanguageFromRequest(Request $request): string
    {
        // Try to detect language from Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');
        
        if ($acceptLanguage) {
            $languages = explode(',', $acceptLanguage);
            foreach ($languages as $lang) {
                $code = strtolower(trim(explode(';', $lang)[0]));
                
                // Check if this language code exists in our system
                if (Language::where('code', $code)->where('is_active', true)->exists()) {
                    return $code;
                }
                
                // Also check for language prefix (e.g., 'en' from 'en-US')
                $prefix = explode('-', $code)[0];
                if (Language::where('code', $prefix)->where('is_active', true)->exists()) {
                    return $prefix;
                }
            }
        }
        
        // Fallback to app default locale
        return config('app.locale', 'en');
    }
}