<?php

namespace App\Services\ContentType;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentType\ContentTypeRepository;

class ContentTypeService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(ContentTypeRepository $repository)
    {
        $this->repository = $repository;
    }
}
