<?php

namespace App\Services\Auth;

use App\Base\BaseService;
use App\Repositories\Auth\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthServices extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials');
        }

        $data['user'] = Auth::user();
        $data['token'] = Auth::user()->createToken('authToken')->accessToken;

        return $data;
    }
}
