<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Get active non-admin users for dropdown selection menus.
     */
    public function getDropdownList()
    {
        return User::active()
            ->whereNotIn('role', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    /**
     * Store a new user in the database.
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Get a paginated list of all users with their total task counts.
     */
    public function paginate()
    {
        return User::withCount('tasks')->latest()->paginate(10);
    }

    /**
     * Find a specific user by ID and include their task count.
     */
    public function find($id)
    {
        return User::withCount('tasks')->findOrFail($id);
    }

    /**
     * Update a user's database record.
     */
    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    /**
     * Standard delete (updates 'deleted_at' if SoftDeletes is on, or wipes if not).
     */
    public function delete(User $user)
    {
        return $user->delete();
    }

    /**
     * Get the human-readable list of roles from the User Model constants.
     */
    public function getRolesList()
    {
        return User::getRoles();
    }

    /**
     * Check if a user has any history in projects or tasks (even deleted ones).
     */
    public function hasHistory(User $user): bool
    {
        return $user->managedProjects()->withTrashed()->exists()
            || $user->tasks()->withTrashed()->exists();
    }

    /**
     * Completely remove a user record from the database hard drive.
     */
    public function physicalDelete(User $user)
    {
        return $user->forceDelete();
    }
}
