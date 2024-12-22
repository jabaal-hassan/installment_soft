<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;

class EmployeeUpdateDTO extends BaseDTOs
{
    public string $position;

    /**
     * Construct the EmployeeUpdateDTO with the input request.
     *
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        $this->position = $data['position'];
    }
}
