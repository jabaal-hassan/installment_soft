<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
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
}
