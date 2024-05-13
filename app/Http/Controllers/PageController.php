<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
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

        return $this->dynamicSuccessResponse('Page/Index', $data, 'inertia');
    }

    public function create()
    {
        $data['contentTypes'] = $this->contentTypeService->getAll([], false);

        return $this->dynamicSuccessResponse('Page/Form', $data, 'inertia');
    }

    public function store(Request $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('page.index', $createData, 'redirect');
    }

    public function edit($PageId)
    {
        $data['item'] = $this->service->find($PageId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        $data['contentTypes'] = $this->contentTypeService->getAll([], false);

        return $this->dynamicSuccessResponse('Page/Form', $data, 'inertia');
    }

    public function update(PageRequest $request, $PageId)
    {
        $updatedData = $this->service->update($PageId, $request->all());

        return $this->dynamicSuccessResponse('content-types.index', $updatedData, 'redirect');
    }

    public function destroy($PageId)
    {
        $this->service->delete($PageId);

        return $this->dynamicSuccessResponse('content-types.index', [], 'redirect');
    }
}
