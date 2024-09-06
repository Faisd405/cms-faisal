<?php

namespace App\Http\Controllers\Collection;

use App\Base\BaseController;
use App\Http\Requests\ContentType\ContentRequest;
use App\Services\Collection\PostService;
use App\Services\Collection\SectionService;
use App\Services\Localization\LanguageService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    protected $service;

    protected $sectionService;

    protected $languageService;

    public function __construct(
        PostService $service,
        SectionService $sectionService,
        LanguageService $languageService
    ) {
        $this->service = $service;

        $this->sectionService = $sectionService;

        $this->languageService = $languageService;
    }

    public function index(Request $request, int $sectionId)
    {
        $data['section'] = $this->sectionService->find($sectionId);

        if (!$data['section']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        $data['list'] = $this->service->getAll([
            'filter' => [
                'section_id' => $sectionId,
            ],
            ...$request->all()
        ]);

        return $this->dynamicSuccessResponse('Collection/Post/Index', $data, 'inertia');
    }

    public function create(int $sectionId)
    {
        $data['section'] = $this->sectionService->find($sectionId);

        if (!$data['section']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Collection/Post/Form', $data, 'inertia');
    }

    public function store(Request $request, int $sectionId)
    {
        $request->merge(['section_id' => $sectionId]);
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('collection.post.index', $createData, 'redirect');
    }

    public function edit(int $sectionId, int $postId, Request $request)
    {
        $data['languages'] = $this->languageService->getAll([], false);

        if ($request->has('locale')) {
            $data['locale'] = $data['languages']->where('iso_code', strtolower($request->get('locale')))->pluck('iso_code')->first();
        }

        if (empty($data['locale'])) {
            $data['locale'] = $data['languages']->where('is_default', true)->pluck('iso_code')->first();
        }

        $data['section'] = $this->sectionService->find($sectionId);

        if (!$data['section']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        $data['item'] = $this->service->find($postId, [
            'with' => ['contentType.fields', 'contentValue.contentTypeField'],
        ]);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Collection/Post/Form', $data, 'inertia');
    }

    public function update(Request $request, int $sectionId, int $postId)
    {
        $updatedData = $this->service->update($postId, $request->all());

        return $this->dynamicSuccessResponse('collection.post.index', $updatedData, 'redirect');
    }

    public function destroy(int $sectionId, int $postId)
    {
        $this->service->delete($postId);

        return $this->dynamicSuccessResponse('collection.post.index', [], 'redirect');
    }


    public function updateContent(ContentRequest $request, int $sectionId, int $postId)
    {
        $updatedData = $this->service->updateContent($postId, $request->all());

        return $this->dynamicSuccessResponse('collection.post.index', $updatedData, 'redirect');
    }
}
