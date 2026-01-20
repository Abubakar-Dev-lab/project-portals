<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function logout()
    {
        $this->authService->logout();
        return redirect('/');
    }
}
