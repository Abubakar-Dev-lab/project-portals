<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials =  $request->validated();
        $remember = $request->has('remember');
        if ($this->authService->login($credentials, $remember)) {
            return redirect()->route('projects.index');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
