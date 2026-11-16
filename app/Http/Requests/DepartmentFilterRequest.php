<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentFilterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'employees' => ['nullable', 'string', 'in:with,without'],
            'employee_status' => ['nullable', 'string', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.string' => 'The search query must be a string.',
            'q.max' => 'The search query may not be greater than 100 characters.',
            'status.in' => 'The status filter must be either active or inactive.',
            'employees.in' => 'The employees filter must be either with or without.',
            'employee_status.in' => 'The employee status filter must be either active or inactive.',
        ];
    }
}
