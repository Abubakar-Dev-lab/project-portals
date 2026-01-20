<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $targetUser)
    {
        if ($targetUser->isSuperAdmin()) {
            return $currentUser->isSuperAdmin();
        }
        return $currentUser->isAdmin();
    }


    public function delete(User $currentUser, User $targetUser)
    {
        // 1. You can NEVER delete yourself
        if ($currentUser->id === $targetUser->id) return false;

        // 2. You can NEVER delete a Super Admin
        if ($targetUser->isSuperAdmin()) return false;

        // 3. If target is an Admin, only a Super Admin can delete them
        if ($targetUser->role === User::ROLE_ADMIN) {
            return $currentUser->isSuperAdmin();
        }

        // 4. Regular Admins can delete Managers and Workers
        return $currentUser->isAdmin();
    }
}
