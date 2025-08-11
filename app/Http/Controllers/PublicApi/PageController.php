<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Http\Resources\Api\PageCollection;
use App\Http\Resources\Api\PageResource;
use App\Services\Page\PageService;
use App\Traits\ApiLocalization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageController extends BaseController
{
    use ApiLocalization;

    protected PageService $service;

    public function __construct(PageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of pages with localization support
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->validateLocaleRequest($request);

            $request->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
                'search' => 'string|max:255',
                'sort' => 'in:title,published_at,created_at,updated_at',
                'direction' => 'in:asc,desc',
            ]);

            $locale = $this->resolveLocale($request);

            // Create cache key based on request parameters
            $cacheKey = 'api_pages_index_' . md5(serialize([
                'locale' => $locale->iso_code,
                'params' => $request->only(['page', 'per_page', 'search', 'sort', 'direction', 'fields', 'include'])
            ]));

            $pages = Cache::remember($cacheKey, 3600, function () use ($request, $locale) {
                $params = array_merge($request->all(), [
                    'filter' => [
                        'localization_id' => $locale->id,
                        'status' => 'published'
                    ],
                    'frontend_service' => true
                ]);

                return $this->service->getAll($params);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pages retrieved successfully',
                'data' => new PageCollection($pages),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in PageController@index', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving pages',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Display the specified page by slug with localization support
     */
    public function show(string $slug, Request $request): JsonResponse
    {
        try {
            // Sanitize slug
            $slug = Str::slug($slug);

            $this->validateLocaleRequest($request);

            $locale = $this->resolveLocale($request);

            // Create cache key
            $cacheKey = "api_page_{$slug}_{$locale->iso_code}_" . md5(serialize([
                'fields' => $request->get('fields'),
                'include' => $request->get('include')
            ]));

            $page = Cache::remember($cacheKey, 3600, function () use ($slug, $locale, $request) {
                return $this->service->findBySlug($slug, $this->buildLocalizationParams($locale, $request));
            });

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found',
                    'error' => [
                        'code' => 'PAGE_NOT_FOUND',
                        'details' => "No page found with slug: {$slug} for locale: {$locale->iso_code}"
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Page retrieved successfully',
                'data' => new PageResource($page),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in PageController@show', [
                'slug' => $slug,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the page',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
