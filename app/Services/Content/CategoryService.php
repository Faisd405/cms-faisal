<?php

namespace App\Services\Content;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Content\CategoryRepository;

class CategoryService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    protected string|array $filterable = [
        'section_id',
    ];

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
}
