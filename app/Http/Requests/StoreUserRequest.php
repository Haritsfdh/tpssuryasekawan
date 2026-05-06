<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use App\Models\User;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'role' => 'nullable',
            'branches' => 'nullable',
            'avatar' =>  [
                          'nullable',
                          File::image()
                              ->max('2mb'),
                        ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name of User is required',
            'username.required' => 'Username is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email is already used',
            'password.required' => 'Password is required',
            'avatar.max' => 'User avatar must not be greater than 2MB (2048 KB).'
        ];
    }
}
