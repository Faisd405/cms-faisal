<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Enums\ContentType;
use App\Http\Requests\ContentType\ContentRequest;
use App\Http\Requests\Page\PageRequest;
use App\Services\Collection\SectionService;
use App\Services\ContentType\ContentTypeService;
use App\Services\Localization\LanguageService;
use App\Services\Page\PageService;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    public function __construct(
        protected PageService $service,
        protected ContentTypeService $contentTypeService,
        protected LanguageService $languageService,
        protected SectionService $sectionService,
    ) {
    }

    public function index(Request $request)
    {
        $input = $request->all();
        $input['with'][] = 'contentType';

        $data['list'] = $this->service->getAll($input);
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

    public function edit($pageId, Request $request)
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
                'type' => ContentType::PAGE->value,
            ],
        ], false);

        $data['item'] = $this->service->find($pageId, [
            'with' => ['contentType.fields', 'contentValue.contentTypeField'],
            'filter' => [
                'localization_id' => $data['locale']->id
            ]
        ]);

        $data['listPages'] = $this->service->getAll(
            [
                'select' => ['id', 'title'],
                'where' => [
                    ['id', '!=', $pageId]
                ]
            ],
            false
        );

        $data['listCollectionSections'] = $this->sectionService->getAll(
            [
                'select' => ['id', 'title'],
            ],
            false
        );

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

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

    public function updateContent(ContentRequest $request, $pageId)
    {
        $updatedData = $this->service->updateContent($pageId, $request->all());

        return $this->dynamicSuccessResponse('page.index', $updatedData, 'redirect');
    }
}
