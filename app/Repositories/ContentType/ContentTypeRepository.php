<?php

namespace App\Repositories\ContentType;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\ContentType\ContentType;

class ContentTypeRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new ContentType);
    }

    public function find(int $id, array $params = [])
    {
        $params['with'] = ['fields'];

        return parent::find($id, $params);
    }
}
