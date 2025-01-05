<?php

namespace App\DTOs\InventoryDTOs;

use App\DTOs\BaseDTOs;

class InventoryCreateDTO extends BaseDTOs
{
    public string $item_name;
    public int $branch_id;
    public int $category_id;
    public int $brand_id;
    public ?string $model;
    public ?string $serial_number;
    public ?string $color;
    public ?string $description;
    public int $quantity;
    public int $price;
    public function __construct($request, $branchId)
    {
        $this->item_name = $request->item_name;
        $this->branch_id = $branchId;
        $this->category_id = $request->category_id;
        $this->brand_id = $request->brand_id;
        $this->model = $request->model;
        $this->serial_number = $request->serial_number;
        $this->color = $request->color;
        $this->description = $request->description;
        $this->quantity = $request->quantity ?? 1; // Default to 1 if not provided
        $this->price = $request->price;
    }

    public function toArray()
    {
        return [
            'item_name' => $this->item_name,
            'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'color' => $this->color,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
