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

    public function edit($id)
    {
        // 1. Get the user to be edited
        $user = $this->userService->getUserById($id);

        // 2. Get the roles from our Model's static method
        $roles = $this->userService->getAvailableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }



    public function update(UpdateUserRequest $request, $id)
    {
        $this->userService->updateProfile($id, $request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        // The Service now handles the logic. We just check the result.
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return back()->with('error', 'Action blocked: You cannot delete yourself or a manager with active projects.');
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
