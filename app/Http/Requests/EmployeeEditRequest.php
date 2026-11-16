<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeEditRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],

            'last_name' => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],

            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:15',
            ],

            'address' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],

            'date_of_birth' => [
                'sometimes',
                'required',
                'date',
                'before:today',
            ],

            'date_of_joining' => [
                'sometimes',
                'required',
                'date',
            ],

            'gender' => [
                'sometimes',
                'required',
                Rule::enum(Gender::class),
            ],

            'department_id' => [
                'sometimes',
                'required',
                'exists:departments,id',
            ],

            'designation' => [
                'sometimes',
                'required',
                'string',
                'max:100',
            ],

            'salary' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }
}
