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

    public function update($userId, array $data)
    {
        $user = User::findOrFail($userId);
        return $user->update($data);
    }

    public function paginate()
    {
        return User::withCount('tasks')->latest()->paginate(10);
    }

    public function findById($id)
    {
        return User::withCount('tasks')->findOrFail($id);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getProjectsCount($id)
    {
        $user = User::findOrFail($id);
        // managedProjects() is the relationship we built earlier
        return $user->managedProjects()->count();
    }
}
