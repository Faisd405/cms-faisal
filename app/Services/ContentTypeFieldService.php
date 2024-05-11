<?php

namespace App\Services;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentTypeFieldRepository;

class ContentTypeFieldService extends BaseService
{
    protected $repository;

    public function __construct(ContentTypeFieldRepository $repository) {
        $this->repository = $repository;
    }


    public function create($data)
    {
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
        $isHaveOrder = isset($data['order']);
        if ($isHaveOrder && $data['order'] != $this->repository->find($id)->order) {
            $this->repository->updateOrder($data['content_type_id'], $this->repository->find($id)->order, $id);
        }

        $this->repository->update($id, $data);

        return $this->repository->find($id);
    }
}
