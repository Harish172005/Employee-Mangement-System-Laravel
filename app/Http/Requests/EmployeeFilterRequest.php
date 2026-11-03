<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Status;
use App\Enums\Gender;

class EmployeeFilterRequest extends FormRequest
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
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['nullable', Rule::enum(Status::class)],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'sort' => ['nullable', Rule::in(['employee_code', 'first_name', 'last_name', 'salary', 'date_of_joining', 'created_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'with_trashed' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'q.string' => 'The search query must be a string.',
            'q.max' => 'The search query may not be greater than 100 characters.',
            'department.integer' => 'The department filter must be an integer.',
            'department.exists' => 'The selected department does not exist.',
            'status.enum' => 'The status filter must be a valid status.',
            'gender.enum' => 'The gender filter must be a valid gender.',
            'sort.in' => 'The sort field must be one of the following: employee_code, first_name, last_name, salary, date_of_joining, created_at.',
            'direction.in' => 'The direction field must be either asc or desc.',
            'with_trashed.boolean' => 'The with_trashed field must be true or false.'
        ];
    }
}
