<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Services\ContentType\ContentTypeService;
use App\Services\Page\PageService;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    protected $service;

    protected $contentTypeService;

    public function __construct(PageService $service, ContentTypeService $contentTypeService)
    {
        $this->service = $service;
        $this->contentTypeService = $contentTypeService;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->successResponse($data, 'Successfully get list pages');
    }

    public function show($slug)
    {
        $data['item'] = $this->service->findBySlug($slug, [
            'with' => ['contentValue'],
        ]);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get page');
    }
}
