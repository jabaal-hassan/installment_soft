<?php

namespace App\DTOs\CustomerDTOs;

use App\DTOs\BaseDTOs;
use App\Models\Inventory;
use App\Models\InstallmentPlan;

class SaleDTO extends BaseDTOs
{
    public string $item_name;
    public int $customer_id;
    public int $branch_id;
    public int $sell_officer_id;
    public ?int $category_id;
    public ?int $brand_id;
    public string $model;
    public ?string $serial_number;
    public ?string $color;
    public ?string $description;
    public int $quantity;
    public float $price;
    public float $advance;
    public float $total_price;
    public string $status;

    public function __construct(Inventory $inventory, InstallmentPlan $installmentPlan, int $customerId, $employee)
    {
        $this->item_name = $inventory->item_name;
        $this->customer_id = $customerId;
        $this->branch_id = $inventory->branch_id;
        $this->sell_officer_id = $employee->id;
        $this->category_id = $inventory->category_id;
        $this->brand_id = $inventory->brand_id;
        $this->model = $inventory->model;
        $this->serial_number = $inventory->serial_number;
        $this->color = $inventory->color;
        $this->description = $inventory->description;
        $this->quantity = $inventory->quantity;
        $this->price = $inventory->price;
        $this->total_price = $installmentPlan->total_price;
        $this->advance = $installmentPlan->advance ?? 0;
        $this->status = 'pending';
    }
}
