<?php

namespace App\Http\Controllers\PublicApi\Collection;

use App\Base\BaseController;
use App\Services\Collection\PostService;
use App\Services\Collection\SectionService;
use App\Services\ContentType\ContentTypeService;
use App\Services\Localization\LanguageService;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    protected $service;

    protected $postService;

    protected $languageService;

    public function __construct(SectionService $service, PostService $postService, LanguageService $languageService)
    {
        $this->service = $service;

        $this->postService = $postService;

        $this->languageService = $languageService;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->successResponse($data, 'Successfully get list sections');
    }

    public function show($sectionslug)
    {
        $data['item'] = $this->service->findBySlug($sectionslug);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get section');
    }

    public function posts($sectionslug)
    {
        $data['list'] = $this->postService->getAllBySectionSlug($sectionslug);

        return $this->successResponse($data, 'Successfully get list posts');
    }

    public function postShow($sectionslug, $postSlug, Request $request)
    {
        $request->validate([
            'locale' => 'sometimes|string',
        ]);

        $getLocaleDefault = $this->languageService->findByDefault();
        if ($request->has('locale')) {
            $getLocale = $this->languageService->findByIsoCode($request->get('locale'));
        }

        if (isset($getLocale)) {
            $data['item'] = $this->postService->findBySlug($sectionslug, $postSlug, [
                'filter' => [
                    'localization_id' => $getLocale->id
                ],
                'append' => [
                    'content'
                ],
            ]);
        }

        if (empty($data['item'])) {
            $data['item'] = $this->postService->findBySlug($sectionslug, $postSlug, [
                'filter' => [
                    'localization_id' => $getLocaleDefault->id
                ],
                'append' => [
                    'content'
                ],
            ]);
        }

        unset($data['item']['contentValue'], $data['item']['contentType']);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get post');
    }
}
