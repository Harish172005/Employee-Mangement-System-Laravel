<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Status;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
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
        $id = $this->route('department')?->id;
        return [
            'name' => ['required', 'string', 'max:100', Rule::unique('departments', 'name')->ignore($id)],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(Status::class)],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The department name is required.',
            'name.string' => 'The department name must be a string.',
            'name.max' => 'The department name may not be greater than 100 characters.',
            'name.unique' => 'The department name has already been taken.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 255 characters.',
            'status.required' => 'The status is required.',
            'status.enum' => 'The selected status is invalid.',
        ];
    }
}
