<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryService;
use App\Http\Requests\StoreInventoryRequest;
use App\DTOs\InventoryDTOs\InventoryCreateDTO;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function getAllCategories()
    {
        return $this->inventoryService->getAllCategories();
    }

    public function getCategoryById($id)
    {
        return $this->inventoryService->getCategoryById($id);
    }

    public function getAllBrands()
    {
        return $this->inventoryService->getAllBrands();
    }

    public function getBrandById($id)
    {
        return $this->inventoryService->getBrandById($id);
    }

    public function getBrandsByCategoryName($categoryName)
    {
        return $this->inventoryService->getBrandsByCategoryName($categoryName);
    }

    public function getAllInventory()
    {
        return $this->inventoryService->getAllInventory();
    }

    public function getInventoryById($id)
    {
        return $this->inventoryService->getInventoryById($id);
    }

    public function getInventoryByBrandName($brandName)
    {
        return $this->inventoryService->getInventoryByBrandName($brandName);
    }

    public function getInventoryByCategoryName($categoryName)
    {
        return $this->inventoryService->getInventoryByCategoryName($categoryName);
    }

    public function storeInventory(StoreInventoryRequest $request)
    {
        return $this->inventoryService->storeInventory($request);
    }

    public function updateInventory($id, StoreInventoryRequest $request)
    {
        return $this->inventoryService->updateInventory($id, $request);
    }

    public function deleteInventory($id)
    {
        return $this->inventoryService->deleteInventory($id);
    }
}
