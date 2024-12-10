<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            "name"=> "required|string",
            "email"=> "required|email|unique:users,id",
            "password"=> "required|string",
            "password_confirmation"=> "required|same:password",
            "avatar"=> "nullable|image",
            "phone" => "required|string|regex:/^0\d{9}$/",
            "role" => "required|string",
            "active" => "required|boolean",
            "department_id" => "required|exists:departments,id"
        ];
    }

    public function messages() {
      return [
        'department_id.exists' => 'Phòng ban không tồn tại'
    ];
    }
}
