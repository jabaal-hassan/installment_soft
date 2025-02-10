<?php

namespace App\DTOs\CustomerDTOs;

use App\DTOs\BaseDTOs;
use App\Models\InstallmentPlan;

class CustomerAccountDTO extends BaseDTOs
{
    public $installment_plan_id;
    public $customer_id;
    public $product_name;
    public $product_price;
    public $installment_duration;
    public $installment_price;
    public $installment_total_price;
    public $remaining_amount;
    public $amount_paid;
    public $status;

    public function __construct($request, $customerId, InstallmentPlan $installmentPlan)
    {
        $this->installment_plan_id = $request->installment_plan_id;
        $this->customer_id = $customerId;
        $this->product_name = $installmentPlan->Product_name;
        $this->product_price = $installmentPlan->product_price;
        $this->installment_duration = $installmentPlan->installment_duration;
        $this->installment_price = $installmentPlan->installment_price;
        $this->installment_total_price = $installmentPlan->total_price;
        $this->remaining_amount = $installmentPlan->remaining_amount;
        $this->amount_paid = $installmentPlan->advance ?? 0;
        $this->status = $request->status ?? 'pending';
    }
}
