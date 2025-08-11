<?php

namespace App\Traits;

use App\Services\Localization\LanguageService;
use Illuminate\Http\Request;

trait ApiLocalization
{
    protected function resolveLocale(Request $request): object
    {
        $languageService = app(LanguageService::class);

        $locale = null;

        if ($request->has('locale')) {
            $requestedLocale = strtolower(trim($request->get('locale')));
            $locale = $languageService->findByIsoCode($requestedLocale);
        }

        if (!$locale) {
            $locale = $languageService->findByDefault();
        }

        if (!$locale) {
            throw new \Exception('No default language configured');
        }

        // Set application locale for any localized content
        app()->setLocale($locale->iso_code);

        return $locale;
    }

    protected function validateLocaleRequest(Request $request): void
    {
        $request->validate([
            'locale' => 'sometimes|string|size:2|alpha|lowercase',
            'fields' => 'sometimes|string|max:255',
            'include' => 'sometimes|string|max:255',
        ]);
    }

    protected function parseFields(Request $request): ?array
    {
        $fields = $request->get('fields');
        return $fields ? array_map('trim', explode(',', $fields)) : null;
    }

    protected function parseIncludes(Request $request): array
    {
        $includes = $request->get('include');
        return $includes ? array_map('trim', explode(',', $includes)) : [];
    }

    protected function buildLocalizationParams(object $locale, Request $request): array
    {
        return [
            'filter' => [
                'localization_id' => $locale->id
            ],
            'append' => ['content'],
            'frontend_service' => true,
            'select' => $this->parseFields($request),
            'with' => $this->parseIncludes($request)
        ];
    }
}
