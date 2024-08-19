<?php

namespace App\Http\Controllers\Collection;

use App\Base\BaseController;
use App\Services\Collection\PostService;
use App\Services\Collection\SectionService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    protected $service;

    protected $sectionService;

    public function __construct(PostService $service, SectionService $sectionService)
    {
        $this->service = $service;

        $this->sectionService = $sectionService;
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

    public function edit(int $sectionId, int $postId)
    {
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


    public function updateContent(Request $request, int $sectionId, int $postId)
    {
        $updatedData = $this->service->updateContent($postId, $request->all());

        return $this->dynamicSuccessResponse('collection.post.index', $updatedData, 'redirect');
    }
}