<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;

class GuarantorRequest extends BaseRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'cnic' => 'required|string|max:15',
            'phone_number' => 'required|string|max:15',
            'relationship' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'office_address' => 'nullable|string|max:255',
            'employment_type' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'cnic_Front_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'cnic_Back_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240',
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
            'customer_id.required' => 'Customer ID is required.',
            'customer_id.exists' => 'Customer ID must exist in the customers table.',
            'name.required' => 'Name is required.',
            'father_name.required' => 'Father name is required.',
            'cnic.required' => 'CNIC is required.',
            'phone_number.required' => 'Phone number is required.',
            'relationship.required' => 'Relationship is required.',
            'address.required' => 'Address is required.',
            'employment_type.required' => 'Employment type is required.',
            'cnic_Front_image.mimes' => 'CNIC front image must be a file of type: jpeg, png, jpg.',
            'cnic_Back_image.mimes' => 'CNIC back image must be a file of type: jpeg, png, jpg.',
            'video.mimes' => 'Video must be a file of type: mp4, mov, avi.',
        ];
    }
}
