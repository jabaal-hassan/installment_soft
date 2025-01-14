<?php

namespace App\Http\Requests\InstallmentPlan;

use App\Http\Requests\BaseRequest;


class InstallmentPlanRequest extends BaseRequest
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
            'plan_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'installment_price' => 'required|numeric|min:0',
            'installment_duration' => 'required|integer|min:1',
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
            'plan_name.required' => 'The plan name field is required.',
            'product_name.required' => 'The product name field is required.',
            'product_price.required' => 'The product price field is required.',
            'advance.required' => 'The advance field is required.',
            'total_price.required' => 'The total price field is required.',
            'remaining_amount.required' => 'The remaining amount field is required.',
            'installment_price.required' => 'The installment price field is required.',
            'installment_duration.required' => 'The installment duration field is required.',
        ];
    }
}
