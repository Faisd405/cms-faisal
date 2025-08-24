<?php

namespace App\Http\Middleware;

use App\Models\Datamaster\Language;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $currentLanguageCode = Session::get('current_language', config('app.locale', 'en'));
        $currentLanguage = Language::where('code', $currentLanguageCode)
            ->where('is_active', true)
            ->first();
            
        $availableLanguages = Language::where('is_active', true)->get();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success'),
            ],
            'language' => [
                'current' => $currentLanguage,
                'available' => $availableLanguages,
            ],
            'seo' => fn () => SeoService::generateMeta(),
            'site' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
            ],
            'ziggy' => fn () => [
                ...\Tightenco\Ziggy\Ziggy::generate(),
            ],
        ]);
    }
}