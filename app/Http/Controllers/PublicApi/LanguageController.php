<?php

namespace App\Http\Controllers\PublicApi;

use App\Base\BaseController;
use App\Services\ContentType\ContentTypeService;
use App\Services\Localization\LanguageService;
use App\Services\Page\PageService;
use Illuminate\Http\Request;

class LanguageController extends BaseController
{
    protected $service;

    public function __construct(
        LanguageService $service,
    ) {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all(), false);

        return $this->successResponse($data, 'Successfully get list languages');
    }
}
