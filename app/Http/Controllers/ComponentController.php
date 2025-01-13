<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Enums\ContentType;
use App\Http\Requests\ContentType\ContentRequest;
use App\Http\Requests\Component\ComponentRequest;
use App\Services\ContentType\ContentTypeService;
use App\Services\Localization\LanguageService;
use App\Services\Component\ComponentService;
use Illuminate\Http\Request;

class ComponentController extends BaseController
{
    protected $service;

    protected $contentTypeService;

    protected $languageService;

    public function __construct(ComponentService $service, ContentTypeService $contentTypeService, LanguageService $languageService)
    {
        $this->service = $service;
        $this->contentTypeService = $contentTypeService;
        $this->languageService = $languageService;
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $input['with'][] = 'contentType';

        $data['list'] = $this->service->getAll($input);
        $data['contentTypes'] = $this->contentTypeService->getAll([
            'filter' => [
                'type' => ContentType::COMPONENT->value
            ]
        ], false);

        return $this->dynamicSuccessResponse('Component/Index', $data, 'inertia');
    }

    public function store(ComponentRequest $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('component.index', $createData, 'redirect');
    }

    public function edit($componentId, Request $request)
    {
        $data['languages'] = $this->languageService->getAll([], false);

        if ($request->has('locale')) {
            $data['locale'] = $data['languages']->where('iso_code', strtolower($request->get('locale')))->first();
        }

        if (empty($data['locale'])) {
            $data['locale'] = $data['languages']->where('is_default', true)->first();
        }

        $data['contentTypes'] = $this->contentTypeService->getAll([
            'filter' => [
                'type' => ContentType::COMPONENT->value,
            ],
        ], false);

        $data['item'] = $this->service->find($componentId, [
            'with' => ['contentType.fields', 'contentValue.contentTypeField'],
            'filter' => [
                'localization_id' => $data['locale']->id
            ]
        ]);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Component/Form', $data, 'inertia');
    }

    public function update(ComponentRequest $request, $componentId)
    {
        $updatedData = $this->service->update($componentId, $request->all());

        return $this->dynamicSuccessResponse('component.index', $updatedData, 'redirect');
    }

    public function destroy($componentId)
    {
        $this->service->delete($componentId);

        return $this->dynamicSuccessResponse('component.index', [], 'redirect');
    }

    public function updateContent(ContentRequest $request, $componentId)
    {
        $updatedData = $this->service->updateContent($componentId, $request->all());

        return $this->dynamicSuccessResponse('component.index', $updatedData, 'redirect');
    }
}
