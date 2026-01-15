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

    public function updateProfile(int $userId, array $data)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }
        if ($userId === auth()->id()) {
            unset($data['role']);
        }
        return $this->userRepo->update($userId, $data);
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
        return User::getRoles();
    }

    public function deleteUser($id)
    {
        // 1. Logic Check: Prevent self-deletion at the service level
        if ((int)$id === (int)auth()->id()) {
            return false;
        }
        // 1. Business Rule: A manager with active projects cannot be deleted
        $projectCount = $this->userRepo->getProjectsCount($id);

        if ($projectCount > 0) {
            // We return false or throw an exception to tell the Controller it failed
            return false;
        }

        // 2. If count is 0, proceed with deletion
        return $this->userRepo->delete($id);
    }
}
