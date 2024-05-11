<?php

namespace App\Services;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\ContentTypeFieldRepository;
use App\Repositories\ContentTypeRepository;

class ContentTypeService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(ContentTypeRepository $repository)
    {
        $this->repository = $repository;
    }
}
