<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getDropdownList()
    {
        return User::active()
            ->whereNotIn('role', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])
            ->orderBy('name')
            ->pluck('name', 'id');
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

    /**
     * Get all available system roles defined in the Model.
     */
    public function getRolesList()
    {
        // The Repository handles the interaction with the Model's static helpers
        return User::getRoles();
    }

    public function hasHistory(User $user): bool
    {
        return $user->managedProjects()->withTrashed()->exists()
            || $user->tasks()->withTrashed()->exists();
    }


    public function physicalDelete(User $user)
    {
        return $user->forceDelete();
    }
}
