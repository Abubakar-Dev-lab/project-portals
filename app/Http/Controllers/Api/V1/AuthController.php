<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Profile\UpdateProfileRequest;

class AuthController extends Controller
{
    use HttpResponses;

    /**
     * Authenticate user and issue API token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->error(
                    [],
                    'Invalid email or password',
                    401
                );
            }

            if (!$user->is_active) {
                return $this->error(
                    [],
                    'This account has been deactivated',
                    403
                );
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return $this->success([
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Login failed',
                500
            );
        }
    }

    /**
     * Register a new user account.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_WORKER,
                'is_active' => true,
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return $this->success([
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Registration successful', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error(
                $e->errors(),
                'Validation failed',
                422
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Registration failed',
                500
            );
        }
    }

    /**
     * Logout user by revoking current API token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            /** @var User $currentUser */
            $currentUser = Auth::user();
            if ($currentUser) {
                $currentUser->currentAccessToken()?->delete();
            }

            return $this->success(
                null,
                'Logout successful'
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Logout failed',
                500
            );
        }
    }

    /**
     * Get the currently authenticated user's profile.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        try {
            $user = Auth::user();

            return $this->success(
                new UserResource($user),
                'Profile retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Failed to retrieve profile',
                500
            );
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $validated = $request->validated();

            // If a password was provided, hash it before saving
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            return $this->success(
                new UserResource($user),
                'Profile updated successfully'
            );
        } catch (\Exception $e) {
            return $this->error(
                ['error' => $e->getMessage()],
                'Profile update failed',
                500
            );
        }
    }
}
