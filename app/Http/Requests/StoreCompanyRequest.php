<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && in_array(Auth::user()->role->role_key, ['master_admin', 'company_admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $companyId = $this->route('company');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->ignore($companyId)
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('companies', 'email')->ignore($companyId)
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:20'
            ],
            'website' => 'nullable|url|max:255',
            'kvk_number' => [
                'required',
                'string',
                'size:8',
                'regex:/^[0-9]{8}$/',
                Rule::unique('companies', 'kvk_number')->ignore($companyId)
            ],
            'contact_name' => 'required|string|max:255',
            'contact_phone' => [
                'required',
                'string',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:20'
            ],
            'contact_email' => 'required|email:rfc,dns|max:255',
            'address' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'nullable|in:active,inactive,suspended',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A company with this name already exists.',
            'email.unique' => 'This email is already registered.',
            'kvk_number.size' => 'The KVK number must be exactly 8 digits.',
            'kvk_number.regex' => 'The KVK number must contain only numbers.',
            'kvk_number.unique' => 'This KVK number is already registered.',
            'phone.regex' => 'The phone number format is invalid.',
            'logo.max' => 'The logo must not be larger than 2MB.',
        ];
    }
}
