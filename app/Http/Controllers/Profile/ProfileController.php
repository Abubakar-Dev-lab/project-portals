<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Services\UserService;

class ProfileController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $userId = auth()->user();
        $this->userService->updateProfile($userId, $request->validated());
        return back()->with('success', 'Profile updated ');
    }
}
