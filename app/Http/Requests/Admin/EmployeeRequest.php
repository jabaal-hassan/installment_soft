<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class EmployeeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // User validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|exists:roles,name',

            // Employee validation rules
            'phone_number' => 'required|string|unique:employees,phone_number|max:11',
            'id_card_number' => 'required|string|unique:employees,id_card_number|max:13|min:13',
            'address' => 'required|string|max:500',
            'position' => 'required|string|max:255',
            'pay' => 'required|integer|min:1',
            'date_of_joining' => 'sometimes|date|before_or_equal:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // User validation messages
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'The role field is required.',
            'role.exists' => 'The selected role does not exist.',

            // Employee validation messages
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.unique' => 'This phone number is already registered.',
            'phone_number.max' => 'The phone number cannot exceed 15 characters.',
            'id_card_number.required' => 'The ID card number field is required.',
            'id_card_number.unique' => 'This ID card number is already registered.',
            'id_card_number.max' => 'The ID card number cannot exceed 50 characters.',
            'address.required' => 'The address field is required.',
            'address.max' => 'The address cannot exceed 500 characters.',
            'position.required' => 'The position field is required.',
            'pay.required' => 'The pay field is required.',
            'pay.integer' => 'The pay must be an integer value.',
            'pay.min' => 'The pay must be a positive number.',
            'date_of_joining.date' => 'The date of joining must be a valid date.',
            'date_of_joining.before_or_equal' => 'The date of joining cannot be in the future.',
        ];
    }
}
