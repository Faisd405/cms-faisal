<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Http\Requests\ContentType\ContentTypeRequest;
use App\Services\ContentTypeFieldService;
use App\Services\ContentTypeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentTypeController extends BaseController
{
    protected $service;

    public function __construct(ContentTypeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->dynamicSuccessResponse('ContentType/Index', $data, 'inertia');
    }

    public function create()
    {
        return $this->dynamicSuccessResponse('ContentType/Form', [], 'inertia');
    }

    public function store(ContentTypeRequest $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('content-types.index', $createData, 'redirect');
    }

    public function edit($contentTypeId)
    {
        $data['item'] = $this->service->find($contentTypeId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('ContentType/Form', $data, 'inertia');
    }

    public function update(ContentTypeRequest $request, $contentTypeId)
    {
        $updatedData = $this->service->update($contentTypeId, $request->all());

        return $this->dynamicSuccessResponse('content-types.index', $updatedData, 'redirect');
    }

    public function destroy($contentTypeId)
    {
        $this->service->delete($contentTypeId);

        return $this->dynamicSuccessResponse('content-types.index', [], 'redirect');
    }
}
