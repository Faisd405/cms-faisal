<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\ContentType\ContentTypeField;

class ContentTypeFieldRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new ContentTypeField);
    }

    public function getMaxOrder($contentTypeId)
    {
        return $this->model->where('content_type_id', $contentTypeId)->max('order');
    }

    public function updateOrder($contentTypeId, $oldOrder, $newId)
    {
        $this->model->where('content_type_id', $contentTypeId)
            ->where('order', '>=', $oldOrder)
            ->where('id', '!=', $newId)
            ->increment('order');

        return $this->model->where('id', $newId)->update(['order' => $oldOrder]);
    }

    public function getByName($contentTypeId, $name, $id = null)
    {
        $query = $this->model->where('content_type_id', $contentTypeId)->where('name', $name);
        if ($id) {
            $query->where('id', '!=', $id);
        }

        return $query->first();
    }
}
