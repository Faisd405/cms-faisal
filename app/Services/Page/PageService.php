<?php

namespace App\Services\Page;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Page\PageRepository;

class PageService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateContent($pageId, $content)
    {
        return $this->repository->updateContent($pageId, $content);
    }
}
