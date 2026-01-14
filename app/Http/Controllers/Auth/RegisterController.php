<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;

class RegisterController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authService->register($validated);
        auth()->login($user);
        return redirect()->route('projects.index');
    }
}
