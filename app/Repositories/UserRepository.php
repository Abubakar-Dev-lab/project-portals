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

    public function paginate()
    {
        return User::withCount('tasks')->latest()->paginate(10);
    }

    public function find($id)
    {
        return User::withCount('tasks')->findOrFail($id);
    }
    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function getProjectsCount(User $user)
    {
        return $user->managedProjects()->count();
    }
}
