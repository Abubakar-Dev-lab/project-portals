<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepo) {}

    public function getUsersForDropdown()
    {
        return $this->userRepo->getDropdownList();
    }
}
