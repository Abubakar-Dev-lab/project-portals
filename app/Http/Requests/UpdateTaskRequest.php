<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
        $user = auth()->user();

        return [
            'project_id' => [
                'sometimes',
                'required',
                // If Admin: Just check if project exists.
                // If Manager: Check if project exists AND belongs to them.
                $user->isAdmin()
                    ? 'exists:projects,id'
                    : Rule::exists('projects', 'id')->where('manager_id', $user->id)
            ],
            'assigned_to' => 'sometimes|required|exists:users,id',
            'title'       => 'sometimes|required|string|max:255',
            'description'      => 'sometimes|required|string',
            'status'      => 'sometimes|required|string|in:todo,in_progress,done',
        ];
    }
    public function messages(): array
    {
        return [
            'project_id.exists' => 'You do not have permission to move a task to this project.',
        ];
    }
}
