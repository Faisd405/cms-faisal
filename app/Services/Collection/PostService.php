<?php

namespace App\Services\Collection;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\Collection\PostRepository;
use App\Traits\UseAttachment;

class PostService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    protected $contentFieldRepository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;

        $this->contentFieldRepository = new ContentTypeFieldRepository();
    }

    public function updateContent($postId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $contentTypeField = $this->contentFieldRepository->find($value['content_type_field_id']);

            if ($contentTypeField->type === 'file') {
                $postContent = $this->repository->getOneContent($postId, $value['content_type_field_id']);

                if ($postContent) {
                    $content['item_content'][$key]['value'] = $this->updateFile($value['value'], $postContent->value, 'uploads/post');
                } else {
                    $content['item_content'][$key]['value'] = $this->uploadFile($value['value'], 'uploads/post');
                }
            }

            // if (in_array($data['type'], ['page', 'collection']) && !isset($data['moduleable_id'])) {
            //     throw new \Exception('Moduleable id is required');
            // }

            // if ($data['type'] == 'page') {
            //     $data['moduleable_type'] = \App\Models\Page\Page::class;
            // } else if ($data['type'] == 'collection') {
            //     $data['moduleable_type'] = \App\Models\Collection\CollectionSection::class;
            // }
        }

        return $this->repository->updateContent($postId, $content);
    }

    public function getAllBySectionSlug($slug)
    {
        return $this->repository->getAllBySectionSlug($slug);
    }

    public function findBySlug($slugSection, $slug, $params = [])
    {
        return $this->repository->findBySlug($slugSection, $slug, $params);
    }
}
