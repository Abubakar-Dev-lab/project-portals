<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getDropdownList()
    {
        return User::orderBy('name')->pluck('name', 'id');
    }

    public function create(array $data)
    {
        return User::create($data);
    }
}
