<?php

namespace App\DTOs\CustomerDTOs;

use App\DTOs\BaseDTOs;

class InstallmentTableDTO extends BaseDTOs
{

    public $customer_id;
    public $customer_account_id;
    public ?int $recived_officer_id;
    public $product_name;
    public $installment_price;
    public ?string $installment_date;
    public $status;


    public function __construct($request, $employee)
    {

        $this->customer_id = $request->customer_id;
        $this->customer_account_id = $request->id;
        $this->recived_officer_id = $employee->id ?? null;
        $this->product_name = $request->product_name;
        $this->installment_price = $request->installment_price;
        $this->installment_date = $request->installment_date ?? null;
        $this->status = "pending";
    }
}
