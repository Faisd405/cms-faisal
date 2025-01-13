<?php

namespace App\Services\Component;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Enums\ContentType;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\ContentType\ContentTypeRepository;
use App\Repositories\Component\ComponentRepository;
use App\Traits\UseAttachment;

class ComponentService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    protected $contentFieldRepository;

    protected $contentTypeRepository;

    public function __construct(ComponentRepository $repository)
    {
        $this->repository = $repository;

        $this->contentTypeRepository = new ContentTypeRepository();
        $this->contentFieldRepository = new ContentTypeFieldRepository();
    }

    public function updateContent($componentId, $content)
    {
        foreach ($content['item_content'] as $key => $value) {
            $contentTypeField = $this->contentFieldRepository->find($value['content_type_field_id']);

            if ($contentTypeField->type === 'file') {
                $componentContent = $this->repository->getOneContent($componentId, $value['content_type_field_id']);

                if ($componentContent) {
                    $content['item_content'][$key]['value'] = $this->updateFile($value['value'], $componentContent->value, 'uploads/component');
                } else {
                    $content['item_content'][$key]['value'] = $this->uploadFile($value['value'], 'uploads/component');
                }
            }
        }

        return $this->repository->updateContent($componentId, $content);
    }

    public function create($data)
    {
        $contentType = $this->contentTypeRepository->find($data['content_type_id']);

        if (!$contentType) {
            throw new \Exception('Content type not found');
        }

        if ($contentType->type !== ContentType::COMPONENT->value) {
            throw new \Exception('Content type is not component');
        }

        return $this->repository->create($data);
    }

    public function findBySlug($id, $params = [])
    {
        return $this->repository->findBySlug($id, $params);
    }
}
