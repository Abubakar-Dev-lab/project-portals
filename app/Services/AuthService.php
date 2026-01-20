<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Inject the User Repository to handle user data persistence.
     */
    public function __construct(protected UserRepository $userRepo) {}

    /**
     * Register a new user and force the 'Worker' role for security.
     */
    public function register(array $data)
    {
        $data['role'] = User::ROLE_WORKER;
        return $this->userRepo->create($data);
    }

    /**
     * Attempt to log the user in and refresh the session ID for security.
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        // verify credentials and set 'remember' cookie if requested
        if (Auth::attempt($credentials, $remember)) {

            // Prevent Session Fixation attacks by changing the session ID
            request()->session()->regenerate();
            return true;
        }
        return false;
    }

    /**
     * Log the user out and completely destroy the current session and CSRF token.
     */
    public function logout(): void
    {
        Auth::logout();

        // Wipe all session data and regenerate the CSRF token for the next guest
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
