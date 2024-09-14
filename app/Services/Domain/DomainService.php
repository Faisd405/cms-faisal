<?php

namespace App\Services\Domain;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Domain\DomainRepository;

class DomainService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new DomainRepository;
    }
}
