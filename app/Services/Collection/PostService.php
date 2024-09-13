<?php

namespace App\Services\Collection;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\Collection\PostRepository;
use App\Repositories\Collection\SectionRepository;
use App\Traits\UseAttachment;

class PostService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    protected $contentFieldRepository;

    protected $sectionRepository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;

        $this->contentFieldRepository = new ContentTypeFieldRepository();

        $this->sectionRepository = new SectionRepository();
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
        }

        return $this->repository->updateContent($postId, $content);
    }

    public function getAllBySectionSlug($sectionSlug, $params = [])
    {
        $sectionId = $this->sectionRepository->findBySlug($sectionSlug, [
            'select' => ['id', 'slug'],
        ])->id;

        $params['section_id'] = $sectionId;

        return $this->repository->getAll($params);
    }

    public function findBySlug($slugSection, $slug, $params = [])
    {
        return $this->repository->findBySlug($slugSection, $slug, $params);
    }
}
