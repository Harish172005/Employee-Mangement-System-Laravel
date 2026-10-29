<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Gender;
use App\Enums\Status;
use App\Rules\MinimumAge;
use Illuminate\Validation\Rule;
class EmployeeRequest extends FormRequest
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
        $id = $this->route('employee')?->id;
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($id)],
            'phone' => ['required', 'string', 'max:10'],
            'date_of_birth' => ['required', 'date', new MinimumAge],
            'date_of_joining' => ['required', 'date'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'address' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(Status::class)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 50 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 50 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 10 characters.',
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'date_of_joining.required' => 'The date of joining is required.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',
            'gender.required' => 'The gender is required.',
            'gender.enum' => 'The selected gender is invalid.',
            'department_id.required' => 'The department is required.',
            'department_id.exists' => 'The selected department does not exist.',
            'designation.required' => 'The designation is required.',
            'designation.string' => 'The designation must be a string.',
            'designation.max' => 'The designation may not be greater than 100 characters.',
            'salary.required' => 'The salary is required.',
            'salary.numeric' => 'The salary must be a number.',
            'salary.min' => 'The salary must be at least 0.',
            'salary.max' => 'The salary may not be greater than 99999999.99.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 5000 characters.',
            'status.required' => 'The status is required.',
            'status.enum' => 'The selected status is invalid.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpg, jpeg, png.',
            'profile_photo.max' => 'The profile photo may not be greater than 2048 kilobytes.',
        ];  
    }
}