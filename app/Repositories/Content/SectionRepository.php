<?php

namespace App\Repositories\Content;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\Content\ContentSection;

class SectionRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new ContentSection());
    }
}
