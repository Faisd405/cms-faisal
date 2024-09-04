<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Enums\ContentType;
use App\Http\Requests\Page\PageRequest;
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
        $data['contentTypes'] = $this->contentTypeService->getAll([
            'filter' => [
                'type' => ContentType::PAGE->value
            ]
        ], false);

        return $this->dynamicSuccessResponse('Page/Index', $data, 'inertia');
    }

    public function store(PageRequest $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('page.index', $createData, 'redirect');
    }

    public function edit($pageId)
    {
        $data['item'] = $this->service->find($pageId, [
            'with' => ['contentType.fields', 'contentValue.contentTypeField'],
        ]);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        $data['contentTypes'] = $this->contentTypeService->getAll([
            'filter' => [
                'type' => ContentType::PAGE->value
            ]
        ], false);

        return $this->dynamicSuccessResponse('Page/Form', $data, 'inertia');
    }

    public function update(PageRequest $request, $pageId)
    {
        $updatedData = $this->service->update($pageId, $request->all());

        return $this->dynamicSuccessResponse('page.index', $updatedData, 'redirect');
    }

    public function destroy($pageId)
    {
        $this->service->delete($pageId);

        return $this->dynamicSuccessResponse('page.index', [], 'redirect');
    }

    public function updateContent(Request $request, $pageId)
    {
        $updatedData = $this->service->updateContent($pageId, $request->all());

        return $this->dynamicSuccessResponse('page.index', $updatedData, 'redirect');
    }
}
