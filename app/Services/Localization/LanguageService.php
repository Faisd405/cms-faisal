<?php

namespace App\Services\Localization;

use App\Base\BaseService;
use App\Base\Construct\BaseServiceInterface;
use App\Repositories\Localization\LanguageRepository;

class LanguageService extends BaseService implements BaseServiceInterface
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new LanguageRepository;
    }

    public function create($data)
    {
        $data['iso_code'] = strtolower($data['iso_code']);

        return $this->repository->create($data);
    }

    public function update($id, $data)
    {
        $data['iso_code'] = strtolower($data['iso_code']);

        $this->repository->update($id, $data);

        return $this->repository->find($id);
    }
}
