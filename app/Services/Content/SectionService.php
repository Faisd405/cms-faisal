<?php

namespace App\Services\Content;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Content\SectionRepository;
use App\Traits\UseAttachment;

class SectionService extends BaseService implements BaseServiceInterface
{
    use UseAttachment;

    protected $repository;

    public function __construct(SectionRepository $repository)
    {
        $this->repository = $repository;
    }
}
