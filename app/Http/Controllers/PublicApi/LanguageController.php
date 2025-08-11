<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Http\Resources\Api\LanguageResource;
use App\Services\Localization\LanguageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageController extends BaseController
{
    protected LanguageService $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of active languages
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'active_only' => 'sometimes|boolean',
            ]);

            $activeOnly = $request->get('active_only', true);
            $cacheKey = "api_languages_index_active_{$activeOnly}";

            $languages = Cache::remember($cacheKey, 7200, function () use ($activeOnly) { // Cache for 2 hours
                $params = ['sort' => 'name:asc'];

                if ($activeOnly) {
                    $params['filter'] = ['is_active' => true];
                }

                return $this->service->getAll($params, false);
            });            return response()->json([
                'success' => true,
                'message' => 'Languages retrieved successfully',
                'data' => LanguageResource::collection($languages),
                'meta' => [
                    'total_languages' => $languages->count(),
                    'default_language' => $languages->where('is_default', true)->first()?->iso_code,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error in LanguageController@index', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving languages',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }
}
