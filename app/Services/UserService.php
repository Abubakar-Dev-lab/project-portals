<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepo,
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo
    ) {}

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
            return ['status' => 'error', 'message' => 'Security restriction: This account cannot be touched.'];
        }

        // 2. Ask Librarian: Is this a mistake account?
        if (!$this->userRepo->hasHistory($user)) {
            $this->userRepo->physicalDelete($user);
            return ['status' => 'success', 'message' => 'Account was a mistake and has been wiped.'];
        }

        $hasProjects = $this->projectRepo->hasPendingProjects($user);
        $hasTasks = $this->taskRepo->hasPendingTasks($user);

        if ($hasProjects || $hasTasks) {
            return ['status' => 'error', 'message' => 'User has unfinished work(Project or Task).'];
        }

        // 4. Decision: If they have history but no pending work, deactivate them.
        $this->userRepo->update($user, ['is_active' => false]);
        return ['status' => 'success', 'message' => 'User deactivated to preserve historical records.'];
    }
}
