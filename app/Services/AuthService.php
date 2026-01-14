<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class AuthtService
{
    public function __construct(protected UserRepository $userRepo) {}

    public function register(array $data)
    {
        $data['role'] = User::ROLE_WORKER;
        $this->userRepo->create($data);
    }
}
