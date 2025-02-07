<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class StoreInventoryRequest extends BaseRequest
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
            'item_name' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
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
            'item_name.required' => 'The item name field is required.',
            'item_name.string' => 'The item name must be a string.',
            'item_name.max' => 'The item name may not be greater than 255 characters.',
            'branch_id.exists' => 'The selected branch ID is invalid.',
            'category_id.exists' => 'The selected category ID is invalid.',
            'brand_id.exists' => 'The selected brand ID is invalid.',
            'model.string' => 'The model must be a string.',
            'model.max' => 'The model may not be greater than 255 characters.',
            'serial_number.string' => 'The serial number must be a string.',
            'serial_number.max' => 'The serial number may not be greater than 255 characters.',
            'color.string' => 'The color must be a string.',
            'color.max' => 'The color may not be greater than 255 characters.',
            'description.string' => 'The description must be a string.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 0.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
        ];
    }
}
