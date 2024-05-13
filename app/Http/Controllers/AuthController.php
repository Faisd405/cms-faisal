<?php

namespace App\Http\Controllers;

use App\Base\BaseController;
use App\Services\Auth\AuthServices;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    protected $service;

    public function __construct(AuthServices $service)
    {
        $this->service = $service;
    }

    public function login(Request $request)
    {
        $data = $this->service->login($request);

        return $this->dynamicSuccessResponse('Auth/Login', $data, 'inertia');
    }
}
