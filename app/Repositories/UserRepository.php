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
    public function update($userId, array $data)
    {
        $user = $this->find($userId);
        return $user->update($data);
    }

    public function delete($id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

    public function getProjectsCount($id)
    {
        $user = $this->find($id);
        // managedProjects() is the relationship we built earlier
        return $user->managedProjects()->count();
    }
}
