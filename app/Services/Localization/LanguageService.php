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

    public function changeDefaultLanguage($languageId)
    {
        $this->repository->changeDefaultLanguage($languageId);
    }
}
