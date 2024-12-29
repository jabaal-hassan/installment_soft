<?php

namespace App\DTOs\BranchDTOs;

class BranchCreateDTO
{
    public string $name;
    public string $address;
    public string $city;
    public int $company_id;

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->address = $request->address;
        $this->city = $request->city;
        $this->company_id = $request->company_id;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'company_id' => $this->company_id,
        ];
    }
}
