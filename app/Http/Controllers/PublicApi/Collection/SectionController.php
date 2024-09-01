<?php

namespace App\Http\Controllers\PublicApi\Collection;

use App\Base\BaseController;
use App\Services\Collection\PostService;
use App\Services\Collection\SectionService;
use App\Services\ContentType\ContentTypeService;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    protected $service;

    protected $postService;

    public function __construct(SectionService $service, PostService $postService)
    {
        $this->service = $service;

        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->successResponse($data, 'Successfully get list sections');
    }

    public function show($slug)
    {
        $data['item'] = $this->service->findBySlug($slug);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get section');
    }

    public function posts($slug)
    {
        $data['list'] = $this->postService->getAllBySectionSlug($slug);

        return $this->successResponse($data, 'Successfully get list posts');
    }

    public function postShow($slug, $postSlug)
    {
        $data['item'] = $this->postService->findBySlug($postSlug, [
            'with' => ['contentValue'],
        ]);

        if (!$data['item']) {
            return $this->errorResponse($data, 'Not Found');
        }

        return $this->successResponse($data, 'Successfully get post');
    }
}
