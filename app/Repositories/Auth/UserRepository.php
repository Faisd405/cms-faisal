<?php

namespace App\Repositories\Auth;

use App\Base\BaseRepository;
use App\Base\Construct\BaseRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements BaseRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new User);
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
