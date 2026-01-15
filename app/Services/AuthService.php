<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct(protected UserRepository $userRepo) {}

    public function register(array $data)
    {
        $data['role'] = User::ROLE_WORKER;
        return $this->userRepo->create($data);
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            request()->session()->regenerate();
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
