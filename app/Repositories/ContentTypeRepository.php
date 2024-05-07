<?php

namespace App\Repositories;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\ContentType\ContentType;

class ContentTypeRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new ContentType);
    }
}
