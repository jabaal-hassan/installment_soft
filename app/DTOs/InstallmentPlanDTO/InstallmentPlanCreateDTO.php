<?php

namespace App\DTOs\InstallmentPlanDTO;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InstallmentPlanCreateDTO extends BaseDTOs
{
    public string $plan_name;
    public ?string $city;
    public string $product_name;
    public string $product_model;
    public float $product_price;
    public float $advance;
    public ?float $percentage;
    public float $total_price;
    public float $remaining_amount;
    public float $installment_price;
    public int $installment_duration;

    public function __construct(Request $request)
    {
        $user = auth()->user();
        $this->plan_name = $request->plan_name;
        $this->city = $user->employee->branch->city ?? null;

        $this->product_name = $request->product_name;
        $this->product_model = $request->product_model;
        $this->product_price = $request->product_price;

        $this->advance = $request->advance;
        $this->percentage = $request->percentage;
        $this->total_price = $request->total_price;
        $this->remaining_amount = $request->remaining_amount;
        $this->installment_price = $request->installment_price;
        $this->installment_duration = $request->installment_duration;
    }
}
