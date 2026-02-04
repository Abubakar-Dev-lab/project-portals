<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct(protected UserService $userService) {}

    /**
     * Display a paginated listing of all users.
     * Admin only endpoint.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $this->authorize('viewAny', User::class);

            $users = $this->userService->getAllUsers();

            return $this->success(
                UserResource::collection($users),
                'Users retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Only admins can view users'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve users',
                500
            );
        }
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        try {
            $this->authorize('view', $user);

            $user = $this->userService->getUserById($user->id);

            return $this->success(
                new UserResource($user),
                'User retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to view this user'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve user',
                500
            );
        }
    }

    /**
     * Update the specified user.
     * Admins can update others; users can update their own profile.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $this->authorize('update', $user);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8',
                'role' => 'sometimes|required|in:super_admin,admin,manager,worker',
                'is_active' => 'sometimes|required|boolean',
            ]);

            $updated = $this->userService->updateProfile($user, $validated);

            if (!$updated) {
                return $this->error(
                    ['authorization' => 'Cannot modify super admin or your own role'],
                    'Update failed',
                    422
                );
            }

            $user = $this->userService->getUserById($user->id);

            return $this->success(
                new UserResource($user),
                'User updated successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to update this user'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to update user',
                500
            );
        }
    }

    /**
     * Delete (deactivate or permanently remove) the specified user.
     * Admin only endpoint.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->authorize('delete', $user);

            $result = $this->userService->deleteUser($user);

            if ($result['status'] === 'error') {
                return $this->error(
                    [],
                    $result['message'],
                    422
                );
            }

            return $this->success(
                null,
                $result['message']
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to delete users'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to delete user',
                500
            );
        }
    }

    /**
     * Reactivate a deactivated user.
     * Admin only endpoint.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function activate(User $user): JsonResponse
    {
        try {
            $this->authorize('update', $user);

            $updated = $this->userService->activateUser($user);

            if (!$updated) {
                return $this->error(
                    [],
                    'Failed to activate user',
                    422
                );
            }

            $user = $this->userService->getUserById($user->id);

            return $this->success(
                new UserResource($user),
                'User activated successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized to activate users'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to activate user',
                500
            );
        }
    }

    /**
     * Get list of available roles for selection/assignment.
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        try {
            $this->authorize('viewAny', User::class);

            $roles = $this->userService->getAvailableRoles();

            return $this->success(
                $roles,
                'Roles retrieved successfully'
            );
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return $this->error(
                ['authorization' => 'Unauthorized'],
                'Access denied',
                403
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve roles',
                500
            );
        }
    }
}
