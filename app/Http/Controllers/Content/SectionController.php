<?php

namespace App\Http\Controllers\Content;

use App\Base\BaseController;
use App\Services\Content\SectionService;
use App\Services\ContentType\ContentTypeService;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    protected $service;

    protected $contentTypeService;

    public function __construct(SectionService $service, ContentTypeService $contentTypeService)
    {
        $this->service = $service;

        $this->contentTypeService = $contentTypeService;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->dynamicSuccessResponse('Content/Section/Index', $data, 'inertia');
    }

    public function create()
    {
        $data['contentTypes'] = $this->contentTypeService->getAll([], false);

        return $this->dynamicSuccessResponse('Content/Section/Form', $data, 'inertia');
    }

    public function store(Request $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('content.section.index', $createData, 'redirect');
    }

    public function edit($sectionId)
    {
        $data['item'] = $this->service->find($sectionId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        $data['contentTypes'] = $this->contentTypeService->getAll([], false);

        return $this->dynamicSuccessResponse('Content/Section/Form', $data, 'inertia');
    }

    public function update(Request $request, $sectionId)
    {
        $updatedData = $this->service->update($sectionId, $request->all());

        return $this->dynamicSuccessResponse('content.section.index', $updatedData, 'redirect');
    }

    public function destroy($sectionId)
    {
        $this->service->delete($sectionId);

        return $this->dynamicSuccessResponse('content.section.index', [], 'redirect');
    }
}
