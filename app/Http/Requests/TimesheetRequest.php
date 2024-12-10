<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetRequest extends FormRequest
{
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
          'check_in_time' => ['nullable', 'time'],
          'check_out_time' => ['nullable', 'time'],
          'created_by' => ['nullable', 'integer', 'exists:users,id'],
          'date' => ['nullable', 'date'],
      ];
  }
}
