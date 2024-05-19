<?php

namespace App\Services\ContentType;

use App\Base\BaseService;
use App\Repositories\ContentType\ContentTypeFieldRepository;
use App\Repositories\Page\PageRepository;

class ContentTypeFieldService extends BaseService
{
    protected $repository;

    protected $pageContentRepository;

    public function __construct(ContentTypeFieldRepository $repository) {
        $this->repository = $repository;

        $this->pageContentRepository = new PageRepository();
    }

    public function create($data)
    {
        $data['name'] = str()->slug($data['name']);

        if ($this->repository->getByName($data['content_type_id'], $data['name'])) {
            throw new \Exception('Field name already exists');
        }

        $isHaveOrder = isset($data['order']);
        if (!isset($data['order'])) {
            $data['order'] = $this->repository->getMaxOrder($data['content_type_id']) + 1;
        }

        $createData = $this->repository->create($data);

        if ($isHaveOrder) {
            $this->repository->updateOrder($data['content_type_id'], $data['order'], $createData->id);
        }

        return $createData;
    }

    public function update($id, $data)
    {
        $data['name'] = str()->slug($data['name']);

        if ($this->repository->getByName($data['content_type_id'], $data['name'], $id)) {
            throw new \Exception('Field name already exists');
        }

        $isHaveOrder = isset($data['order']);
        if ($isHaveOrder && $data['order'] != $this->repository->find($id)->order) {
            $this->repository->updateOrder($data['content_type_id'], $this->repository->find($id)->order, $id);
        }

        $this->repository->update($id, $data);

        return $this->repository->find($id);
    }

    public function delete($id)
    {
        $this->pageContentRepository->deleteContentByFieldId($id);

        return $this->repository->delete($id);
    }
}
