<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manager_id' => auth()->user()->isAdmin() ?
                'required|exists:users,id' : 'prohibited',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'nullable|string|in:pending,active,completed',
        ];
    }
}
