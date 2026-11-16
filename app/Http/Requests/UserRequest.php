<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'The username is required.',
            'username.string' => 'The username must be a string.',
            'username.max' => 'The username may not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'role.required' => 'The role is required.',
            'role.string' => 'The role must be a string.',
            'role.max' => 'The role may not be greater than 255 characters.',
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.max' => 'The status may not be greater than 255 characters.',
            'password.required' => 'The password is required.',
            'password.password' => 'The password must meet the password requirements.',
            'password.min' => 'The password must be at least 8 characters long.',
        ];
    }   
}
