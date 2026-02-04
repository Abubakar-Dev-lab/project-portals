<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        /** @var User|null $currentUser */
        $currentUser = Auth::user();

        return $currentUser && ($currentUser->isAdmin() || $currentUser->id === $user->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        /** @var User|null $currentUser */
        $currentUser = Auth::user();

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => $currentUser?->isAdmin() ? 'sometimes|required|in:worker,manager,admin' : 'prohibited',
            'is_active' => $currentUser?->isAdmin() ? 'sometimes|required|boolean' : 'prohibited',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already in use.',
            'password.min' => 'Password must be at least 8 characters long.',
            'role.prohibited' => 'You cannot change user roles.',
        ];
    }
}
