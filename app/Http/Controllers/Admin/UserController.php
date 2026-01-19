<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return  view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = $this->userService->getAvailableRoles();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateProfile($user, $request->validated());
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Security Block: You cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Security Block: Super Admins cannot be removed.');
        }

        $result = $this->userService->deleteUser($user);

        if ($result['status'] === 'error') {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('admin.users.index')->with('success', $result['message']);
    }

    public function activate(User $user)
    {
        // 1. The Controller asks the Service to handle the logic
        $this->userService->activateUser($user);

        // 2. Redirect back with a success message
        return back()->with('success', 'User account has been reactivated.');
    }
}
