<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class BranchRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:branches,name,NULL,id,city,' . $this->city
            ],
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'company_id' => 'exists:companies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required',
            'name.max' => 'Branch name cannot exceed 255 characters',
            'name.unique' => 'A branch with this name already exists in this city',
            'address.required' => 'Branch address is required',
            'city.required' => 'City name is required',
            'city.max' => 'City name cannot exceed 255 characters',
            'company_id.exists' => 'Selected company does not exist',
        ];
    }
}
