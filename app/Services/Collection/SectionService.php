<?php

namespace App\Services\Collection;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Collection\SectionRepository;
use App\Traits\UseAttachment;

class SectionService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    public function __construct(SectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findBySlug($id, $params = [])
    {
        return $this->repository->findBySlug($id, $params);
    }
}
