<?php

namespace App\Http\Controllers\Content;

use App\Base\BaseController;
use App\Services\Content\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->dynamicSuccessResponse('Content/Category/Index', $data, 'inertia');
    }

    public function create()
    {
        return $this->dynamicSuccessResponse('Content/Category/Form', [], 'inertia');
    }

    public function store(Request $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('content.category.index', $createData, 'redirect');
    }

    public function edit($categoryId)
    {
        $data['item'] = $this->service->find($categoryId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Content/Category/Form', $data, 'inertia');
    }

    public function update(Request $request, $categoryId)
    {
        $updatedData = $this->service->update($categoryId, $request->all());

        return $this->dynamicSuccessResponse('content.category.index', $updatedData, 'redirect');
    }

    public function destroy($categoryId)
    {
        $this->service->delete($categoryId);

        return $this->dynamicSuccessResponse('content.category.index', [], 'redirect');
    }
}
