<?php

namespace App\Http\Requests;

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
        return [
            'project_id'  => 'sometimes|required|exists:projects,id',
            'assigned_to' => 'sometimes|required|exists:users,id',
            'title'       => 'sometimes|required|string|max:255',
            'description'      => 'sometimes|required|string',
            'status'      => 'sometimes|required|string|in:todo,in_progress,done',
        ];
    }
}
