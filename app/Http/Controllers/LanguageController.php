<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Http\Requests\Localization\LanguageRequest;
use App\Services\Localization\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends BaseController
{
    protected $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['list'] = $this->service->getAll($request->all());

        return $this->dynamicSuccessResponse('Language/Index', $data, 'inertia');
    }

    public function store(LanguageRequest $request)
    {
        $createData = $this->service->create($request->all());

        return $this->dynamicSuccessResponse('language.index', $createData, 'redirect');
    }

    public function edit($languageId)
    {
        $data['item'] = $this->service->find($languageId);

        if (!$data['item']) {
            return $this->dynamicErrorResponse('404', [], 'inertia');
        }

        return $this->dynamicSuccessResponse('Language/Form', $data, 'inertia');
    }

    public function update(LanguageRequest $request, $languageId)
    {
        $updatedData = $this->service->update($languageId, $request->all());

        return $this->dynamicSuccessResponse('language.index', $updatedData, 'redirect');
    }

    public function destroy($languageId)
    {
        $this->service->delete($languageId);

        return $this->dynamicSuccessResponse('language.index', [], 'redirect');
    }
}
