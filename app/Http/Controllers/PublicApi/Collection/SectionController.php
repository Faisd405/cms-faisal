<?php

namespace App\Http\Controllers\PublicApi\Collection;

use App\Base\BaseController;
use App\Http\Resources\Api\PostCollection;
use App\Http\Resources\Api\PostResource;
use App\Http\Resources\Api\SectionCollection;
use App\Http\Resources\Api\SectionResource;
use App\Services\Collection\PostService;
use App\Services\Collection\SectionService;
use App\Traits\ApiLocalization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SectionController extends BaseController
{
    use ApiLocalization;

    protected SectionService $service;
    protected PostService $postService;

    public function __construct(SectionService $service, PostService $postService)
    {
        $this->service = $service;
        $this->postService = $postService;
    }

    /**
     * Display a listing of sections
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
                'search' => 'string|max:255',
                'sort' => 'in:title,created_at,updated_at',
                'direction' => 'in:asc,desc',
            ]);

            // Create cache key
            $cacheKey = 'api_sections_index_' . md5(serialize($request->all()));

            $sections = Cache::remember($cacheKey, 1800, function () use ($request) {
                return $this->service->getAll($request->all());
            });

            return response()->json([
                'success' => true,
                'message' => 'Sections retrieved successfully',
                'data' => new SectionCollection($sections),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in SectionController@index', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving sections',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Display the specified section by slug
     */
    public function show(string $sectionSlug): JsonResponse
    {
        try {
            // Sanitize slug
            $sectionSlug = Str::slug($sectionSlug);

            $cacheKey = "api_section_{$sectionSlug}";

            $section = Cache::remember($cacheKey, 1800, function () use ($sectionSlug) {
                return $this->service->findBySlug($sectionSlug);
            });

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found',
                    'error' => [
                        'code' => 'SECTION_NOT_FOUND',
                        'details' => "No section found with slug: {$sectionSlug}"
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Section retrieved successfully',
                'data' => new SectionResource($section),
            ]);

        } catch (\Exception $e) {
            \Log::error('API Error in SectionController@show', [
                'slug' => $sectionSlug,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the section',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Display posts in a specific section with localization support
     */
    public function posts(string $sectionSlug, Request $request): JsonResponse
    {
        try {
            // Sanitize slug
            $sectionSlug = Str::slug($sectionSlug);

            $this->validateLocaleRequest($request);

            $request->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
                'search' => 'string|max:255',
                'sort' => 'in:title,published_at,created_at,updated_at',
                'direction' => 'in:asc,desc',
            ]);

            $locale = $this->resolveLocale($request);

            // Create cache key
            $cacheKey = "api_section_{$sectionSlug}_posts_{$locale->iso_code}_" . md5(serialize($request->all()));

            $posts = Cache::remember($cacheKey, 900, function () use ($sectionSlug, $locale, $request) {
                $params = array_merge($request->all(), [
                    'filter' => [
                        'localization_id' => $locale->id,
                        'status' => 'published'
                    ],
                    'frontend_service' => true
                ]);

                return $this->postService->getAllBySectionSlug($sectionSlug, $params);
            });

            return response()->json([
                'success' => true,
                'message' => 'Posts retrieved successfully',
                'data' => new PostCollection($posts),
                'meta' => [
                    'section_slug' => $sectionSlug,
                    'locale' => $locale->iso_code,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in SectionController@posts', [
                'section_slug' => $sectionSlug,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving posts',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Display a specific post by slug with localization fallback
     */
    public function postShow(string $sectionSlug, string $postSlug, Request $request): JsonResponse
    {
        try {
            // Sanitize slugs
            $sectionSlug = Str::slug($sectionSlug);
            $postSlug = Str::slug($postSlug);

            $this->validateLocaleRequest($request);

            $locale = $this->resolveLocale($request);

            // Create cache key
            $cacheKey = "api_post_{$sectionSlug}_{$postSlug}_{$locale->iso_code}_" . md5(serialize([
                'fields' => $request->get('fields'),
                'include' => $request->get('include')
            ]));

            $post = Cache::remember($cacheKey, 900, function () use ($sectionSlug, $postSlug, $locale, $request) {
                $params = $this->buildLocalizationParams($locale, $request);

                // Try to find post in requested locale
                $post = $this->postService->findBySlug($sectionSlug, $postSlug, $params);

                // If not found, try default locale as fallback
                if (!$post) {
                    $defaultLocale = app(\App\Services\Localization\LanguageService::class)->findByDefault();
                    if ($defaultLocale && $defaultLocale->id !== $locale->id) {
                        $fallbackParams = $this->buildLocalizationParams($defaultLocale, $request);
                        $post = $this->postService->findBySlug($sectionSlug, $postSlug, $fallbackParams);

                        if ($post) {
                            // Set locale to the fallback locale
                            app()->setLocale($defaultLocale->iso_code);
                        }
                    }
                }

                return $post;
            });            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post not found',
                    'error' => [
                        'code' => 'POST_NOT_FOUND',
                        'details' => "No post found with slug: {$postSlug} in section: {$sectionSlug} for locale: {$locale->iso_code}"
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Post retrieved successfully',
                'data' => new PostResource($post),
                'meta' => [
                    'section_slug' => $sectionSlug,
                    'fallback_used' => app()->getLocale() !== $locale->iso_code,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in SectionController@postShow', [
                'section_slug' => $sectionSlug,
                'post_slug' => $postSlug,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the post',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
