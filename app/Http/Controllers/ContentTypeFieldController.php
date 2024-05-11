<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Http\Requests\ContentType\FieldRequest;
use App\Services\ContentTypeFieldService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentTypeFieldController extends BaseController
{
    protected $service;

    public function __construct(ContentTypeFieldService $service)
    {
        $this->service = $service;
    }

    public function store(FieldRequest $request, $contentTypeId)
    {
        $data = $request->all();
        $data['content_type_id'] = $contentTypeId;

        $createData = $this->service->create($data);

        return $this->successResponse($createData, 'Field created successfully');
    }

    public function update(Request $request, $contentTypeId, $fieldId)
    {
        $data = $request->all();
        $data['content_type_id'] = $contentTypeId;

        $updatedData = $this->service->update($fieldId, $data);

        return $this->successResponse($updatedData, 'Field updated successfully');
    }

    public function destroy($contentTypeId, $fieldId)
    {
        $this->service->delete($fieldId, $contentTypeId);

        return $this->successResponse(null, 'Field deleted successfully');
    }
}