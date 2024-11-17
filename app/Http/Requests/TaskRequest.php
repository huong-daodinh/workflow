<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
          'title' => 'required|string|min:5|max:255',
          'priority' => 'nullable|string',
          'description' => 'required|string',
          'status' => 'nullable|string',
          'result' => 'nullable|string',
          'task_list_id' => 'nullable|integer|exists:task_lists,id',
          'project_id' => 'nullable|integer|exists:projects,id',
          'created_by' => 'required|integer',
          'assigner_id' => 'required|integer|exists:users,id',
          'due_date' => 'required|date',
          'assignee_id' => 'required|integer|exists:users,id',
          'started_at' => 'nullable|date',
        ];
    }
}
