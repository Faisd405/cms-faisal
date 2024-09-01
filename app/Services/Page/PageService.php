<?php

namespace App\Services\Page;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Enums\ContentType;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\ContentType\ContentTypeRepository;
use App\Repositories\Page\PageRepository;
use App\Traits\UseAttachment;

class PageService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    protected $contentFieldRepository;

    protected $contentTypeRepository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;

        $this->contentTypeRepository = new ContentTypeRepository();
        $this->contentFieldRepository = new ContentTypeFieldRepository();
    }

    public function updateContent($pageId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $contentTypeField = $this->contentFieldRepository->find($value['content_type_field_id']);

            if ($contentTypeField->type === 'file') {
                $pageContent = $this->repository->getOneContent($pageId, $value['content_type_field_id']);

                if ($pageContent) {
                    $content['item_content'][$key]['value'] = $this->updateFile($value['value'], $pageContent->value, 'uploads/page');
                } else {
                    $content['item_content'][$key]['value'] = $this->uploadFile($value['value'], 'uploads/page');
                }
            }
        }

        return $this->repository->updateContent($pageId, $content);
    }

    public function create($data)
    {
        $contentType = $this->contentTypeRepository->find($data['content_type_id']);

        if (!$contentType) {
            throw new \Exception('Content type not found');
        }

        if ($contentType->type !== ContentType::PAGE->value) {
            throw new \Exception('Content type is not page');
        }

        return $this->repository->create($data);
    }

    public function findBySlug($id, $params = [])
    {
        return $this->repository->findBySlug($id, $params);
    }
}
