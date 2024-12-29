<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class StoreCompanyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Adjust this if you want to restrict access based on user roles
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',  // Ensures company name is provided and doesn't exceed 255 characters
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validates the logo (optional but must be an image with allowed formats and a size limit)
        ];
    }
}
