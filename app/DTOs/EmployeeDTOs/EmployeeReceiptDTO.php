<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;

class EmployeeReceiptDTO extends BaseDTOs
{
    public int $employee_id;
    public string $customer_name;
    public string $payment_type;
    public float $amount;


    public function __construct($data, $employee)
    {
        $this->employee_id = $employee->id;
        $this->customer_name = $data['customer_name'];
        $this->payment_type = $data['payment_type'];
        $this->amount = $data['amount'];
    }
}
