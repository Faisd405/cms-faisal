<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Services\ContentType\ContentTypeService;
use App\Services\Localization\LanguageService;
use App\Services\Page\PageService;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    protected $service;

    protected $contentTypeService;

    protected $languageService;

    public function __construct(
        PageService $service,
        ContentTypeService $contentTypeService,
        LanguageService $languageService
    ) {
        $this->service = $service;
        $this->contentTypeService = $contentTypeService;
        $this->languageService = $languageService;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->successResponse($data, 'Successfully get list pages');
    }

    public function show($slug, Request $request)
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

        $data['item'] = null;

        $data['item'] = $this->service->findBySlug($slug, [
            'filter' => [
                'localization_id' => $getLocale->id
            ],
            'append' => [
                'content'
            ],
            'frontend_service' => true
        ]);

        unset($data['item']['contentValue'], $data['item']['contentType']);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get page');
    }
}
