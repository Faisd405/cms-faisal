<?php

namespace App\Services;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Models\User;
use App\Repositories\ContentTypeRepository;
use App\Repositories\UserRepository;

class ContentTypeService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(ContentTypeRepository $repository) {
        $this->repository = $repository;
    }
}
