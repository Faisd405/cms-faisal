<?php

namespace App\Repositories\Collection;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Collection\CollectionSection;

class SectionRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new CollectionSection());
    }

    public function findBySlug($slug, $params = [])
    {
        return $this->prepareQuery($params)->where('slug', $slug)->first();
    }
}
