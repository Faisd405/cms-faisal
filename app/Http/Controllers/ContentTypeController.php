<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
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

    public function store(Request $request)
    {
        $this->service->create($request->all());

        return $this->dynamicSuccessResponse('content-types.index', [], 'redirect');
    }

    public function edit($id)
    {
        $data['item'] = $this->service->find($id);

        return $this->dynamicSuccessResponse('ContentType/Form', $data, 'inertia');
    }

    public function update(Request $request, $id)
    {
        $this->service->update($id, $request->all());

        return $this->dynamicSuccessResponse('content-types.index', [], 'redirect');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return $this->dynamicSuccessResponse('content-types.index', [], 'redirect');
    }
}
