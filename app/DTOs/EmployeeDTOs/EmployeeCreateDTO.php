<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;

class EmployeeCreateDTO extends BaseDTOs
{
    public int $user_id;
    public string $name;
    public string $phone_number;
    public string $id_card_number;
    public string $address;
    public string $position;
    public string $date_of_joining;
    public int $pay;

    /**
     * Construct the EmployeeCreateDTO with the input request.
     */
    public function __construct($data, int $userId)
    {
        $this->user_id = $userId;
        $this->name = $data['name'];
        $this->phone_number = $data['phone_number'];
        $this->id_card_number = $data['id_card_number'];
        $this->address = $data['address'];
        $this->position = $data['position'];
        $this->date_of_joining = $data['date_of_joining'] ?? now()->toDateString();
        $this->pay = $data['pay'];
    }
}
