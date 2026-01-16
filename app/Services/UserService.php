<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepo) {}

    public function getUsersForDropdown()
    {
        return $this->userRepo->getDropdownList();
    }

    public function updateProfile(User $user, array $data)
    {
        $currentUser = auth()->user();
        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            return false;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($user->is($currentUser)) {
            unset($data['role']);
        }

        // if ($user->is(auth()->user())) {
        //     unset($data['role']);
        // }
        return $this->userRepo->update($user, $data);
    }

    public function getAllUsers()
    {
        return $this->userRepo->paginate();
    }

    public function getUserById($id)
    {
        return $this->userRepo->find($id);
    }

    public function getAvailableRoles()
    {
        // The service provides the roles defined in the Model constants
        return $this->userRepo->getRolesList();
    }

    public function deleteUser(User $user)
    {
        if ($user->isSuperAdmin() || $user->is(auth()->user())) {
            return false;
        }

        // 1. Business Rule: A manager with active projects cannot be deleted
        $projectCount = $this->userRepo->getProjectsCount($user);

        if ($projectCount > 0) {
            // We return false or throw an exception to tell the Controller it failed
            return false;
        }

        // 2. If count is 0, proceed with deletion
        return $this->userRepo->delete($user);
    }
}
