<?php

namespace App\Http\Controllers\Collection;

use App\Base\BaseController;
use App\Services\Collection\CategoryService;
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

        return $this->dynamicSuccessResponse('Collection/Category/Index', $data, 'inertia');
    }

    public function create()
    {
        return $this->dynamicSuccessResponse('Collection/Category/Form', [], 'inertia');
    }

    public function store(Request $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('collection.category.index', $createData, 'redirect');
    }

    public function edit($categoryId)
    {
        $data['item'] = $this->service->find($categoryId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Collection/Category/Form', $data, 'inertia');
    }

    public function update(Request $request, $categoryId)
    {
        $updatedData = $this->service->update($categoryId, $request->all());

        return $this->dynamicSuccessResponse('collection.category.index', $updatedData, 'redirect');
    }

    public function destroy($categoryId)
    {
        $this->service->delete($categoryId);

        return $this->dynamicSuccessResponse('collection.category.index', [], 'redirect');
    }
}
