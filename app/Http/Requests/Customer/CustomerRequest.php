<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\BaseRequest;


class CustomerRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:customers,phone_number',
            'cnic' => 'required|string|unique:customers,cnic',
            'address' => 'required|string',
            'office_address' => 'required|string',
            'employment_type' => 'nullable|string',
            'company_name' => 'nullable|string',
            'years_of_experience' => 'nullable|integer',
            'cnic_front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cnic_back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'customer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'check_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,flv|max:10240',
            'status' => 'nullable|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'father_name.required' => 'The father name field is required.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.unique' => 'The phone number has already been taken.',
            'cnic.required' => 'The CNIC field is required.',
            'cnic.unique' => 'The CNIC has already been taken.',
            'address.required' => 'The address field is required.',
            'office_address.required' => 'The office address field is required.',
            'cnic_front_image.required' => 'The CNIC front image is required.',
            'cnic_front_image.image' => 'The CNIC front image must be an image file.',
            'cnic_front_image.mimes' => 'The CNIC front image must be a file of type: jpeg, png, jpg, gif.',
            'cnic_front_image.max' => 'The CNIC front image may not be greater than 2048 kilobytes.',
            'cnic_back_image.required' => 'The CNIC back image is required.',
            'cnic_back_image.image' => 'The CNIC back image must be an image file.',
            'cnic_back_image.mimes' => 'The CNIC back image must be a file of type: jpeg, png, jpg, gif.',
            'cnic_back_image.max' => 'The CNIC back image may not be greater than 2048 kilobytes.',
            'customer_image.image' => 'The customer image must be an image file.',
            'customer_image.mimes' => 'The customer image must be a file of type: jpeg, png, jpg, gif.',
            'customer_image.max' => 'The customer image may not be greater than 2048 kilobytes.',
            'check_image.image' => 'The check image must be an image file.',
            'check_image.mimes' => 'The check image must be a file of type: jpeg, png, jpg, gif.',
            'check_image.max' => 'The check image may not be greater than 2048 kilobytes.',
            'video.mimes' => 'The video must be a file of type: mp4, mov, avi, flv.',
            'video.max' => 'The video may not be greater than 10240 kilobytes.',
        ];
    }
}
