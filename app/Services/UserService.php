<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProjectRepository;

class UserService
{
    /**
     * Inject repositories to handle cross-module logic between users, projects, and tasks.
     */
    public function __construct(
        protected UserRepository $userRepo,
        protected ProjectRepository $projectRepo,
        protected TaskRepository $taskRepo
    ) {}

    /**
     * Fetch users for dropdown selection lists.
     */
    public function getUsersForDropdown()
    {
        return $this->userRepo->getDropdownList();
    }

    /**
     * Handle profile updates with security checks for hierarchy, empty passwords, and self-role changes.
     */
    public function updateProfile(User $user, array $data)
    {
        $currentUser = auth()->user();

        // Stop regular admins from editing the system owner (Super Admin)
        if ($user->isSuperAdmin() && !$currentUser->isSuperAdmin()) {
            return false;
        }

        // Only update password if a new one was actually typed
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Prevent users from changing their own permission levels
        if ($user->is($currentUser)) {
            unset($data['role']);
        }

        return $this->userRepo->update($user, $data);
    }

    /**
     * Get a paginated list of all users for the Admin panel.
     */
    public function getAllUsers()
    {
        return $this->userRepo->paginate();
    }

    /**
     * Get a single user by their database ID.
     */
    public function getUserById($id)
    {
        return $this->userRepo->find($id);
    }

    /**
     * Get the list of allowed roles (Worker, Manager, Admin, etc.)
     */
    public function getAvailableRoles()
    {
        return $this->userRepo->getRolesList();
    }


    /**
     * Judge whether to physically delete, deactivate, or block a user removal.
     */
    public function deleteUser(User $user)
    {
        // Guard against self-deletion or deleting the system owner
        if ($user->isSuperAdmin() || $user->is(auth()->user())) {
            return ['status' => 'error', 'message' => 'Security restriction: This account cannot be touched.'];
        }

        // If user has zero history (mistake account), physically remove the row
        if (!$this->userRepo->hasHistory($user)) {
            $this->userRepo->physicalDelete($user);
            return ['status' => 'success', 'message' => 'Account has no history so permanent delete.'];
        }

        // Block removal if there is still active work assigned to the user
        $hasUnfinishedProjects = $this->projectRepo->hasPendingProjects($user);
        $hasUnfinishedTasks = $this->taskRepo->hasPendingTasksForUser($user);

        if ($hasUnfinishedProjects || $hasUnfinishedTasks) {
            return ['status' => 'error', 'message' => 'User has unfinished work(Project or Task).'];
        }

        // If history exists but work is done, deactivate the login but keep the record        $this->userRepo->update($user, ['is_active' => false]);
        return ['status' => 'success', 'message' => 'User deactivated to preserve historical records.'];
    }

    /**
     * Restore access for a deactivated user.
     */
    public function activateUser(User $user)
    {
        return $this->userRepo->update($user, ['is_active' => true]);
    }
}
