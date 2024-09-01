<?php

namespace App\Repositories\Collection;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Collection\CollectionCategory;

class CategoryRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new CollectionCategory());
    }

    public function findBySlug($slug, $params = [])
    {
        return $this->prepareQuery($params)->where('slug', $slug)->first();
    }
}
