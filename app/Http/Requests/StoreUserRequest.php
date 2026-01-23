<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'role_id' => 'required|exists:roles,id',
            'company_id' => 'nullable|exists:companies,id',
            'username' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($userId)
            ],
        ];

        // Password is required only on create, optional on update
        if (!$isUpdate) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ];
        } else {
            $rules['password'] = [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'The username may only contain letters, numbers, dashes and underscores.',
            'password.confirmed' => 'The password confirmation does not match.',
            'company_id.exists' => 'The selected company is invalid.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }
}
