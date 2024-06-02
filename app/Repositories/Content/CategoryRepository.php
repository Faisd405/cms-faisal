<?php

namespace App\Repositories\Content;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Content\ContentCategory;

class CategoryRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new ContentCategory());
    }
}
