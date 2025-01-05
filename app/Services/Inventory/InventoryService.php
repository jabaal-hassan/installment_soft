<?php

namespace App\Services\Inventory;

use App\Models\Brand;
use App\Models\Branch;
use App\Helpers\Helpers;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Inventory;
use App\Constants\Messages;
use Illuminate\Support\Facades\DB;
use App\DTOs\InventoryDTOs\InventoryCreateDTO;
use Symfony\Component\HttpFoundation\Response;

class InventoryService
{
    /**
     * Get all categories.
     *
     * @return array
     */
    public function getAllCategories()
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return Helpers::result('No categories found', Response::HTTP_NOT_FOUND);
        }
        return Helpers::result('Categories retrieved successfully', Response::HTTP_OK, [
            'Categories' => $categories
        ]);
    }

    /**
     * Get a category by ID.
     *
     * @param int $id
     * @return array
     */
    public function getCategoryById($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return Helpers::result('Category not found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Category retrieved successfully', Response::HTTP_OK, [
            'Category' => $category
        ]);
    }

    /**
     * Get all brands.
     *
     * @return array
     */
    public function getAllBrands()
    {
        $brands = Brand::all();
        if ($brands->isEmpty()) {
            return Helpers::result('No brands found', Response::HTTP_NOT_FOUND);
        }
        return Helpers::result('Brands retrieved successfully', Response::HTTP_OK, [
            'Brands' => $brands
        ]);
    }

    /**
     * Get a brand by ID.
     *
     * @param int $id
     * @return array
     */
    public function getBrandById($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return Helpers::result('Brand not found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Brand retrieved successfully', Response::HTTP_OK, [
            'Brand' => $brand
        ]);
    }

    /**
     * Get brands by category name.
     *
     * @param string $categoryName
     * @return array
     */
    public function getBrandsByCategoryName($categoryName)
    {
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return Helpers::result('Category not found', Response::HTTP_NOT_FOUND);
        }

        $brands = $category->brands;
        if ($brands->isEmpty()) {
            return Helpers::result('No brands found for this category', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Brands retrieved successfully', Response::HTTP_OK, [
            'Brands' => $brands
        ]);
    }

    /**
     * Get all inventory items.
     *
     * @return array
     */
    public function getAllInventory()
    {
        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'N/A';
        $admin = $user->employee;

        $query = Inventory::query();
        // Check the role and filter accordingly
        if ($role === 'company admin') {
            $companyId = $admin->company_id;
            $query->whereHas('branch', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        } elseif ($role === 'branch admin') {
            $branchId = $admin->branch_id;
            $query->where('branch_id', $branchId);
        }

        $inventory = $query->get();

        if ($inventory->isEmpty()) {
            return Helpers::result('No inventory items found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Inventory items retrieved successfully', Response::HTTP_OK, [
            'inventory' => $inventory
        ]);
    }


    /**
     * Get a single inventory item by ID.
     *
     * @param int $id
     * @return array
     */
    public function getInventoryById($id)
    {

        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'N/A';
        $admin = $user->employee;

        $query = Inventory::query();
        // Check the role and filter accordingly
        if ($role === 'company admin') {
            $companyId = $admin->company_id;
            $query->whereHas('branch', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        } elseif ($role === 'branch admin') {
            $branchId = $admin->branch_id;
            $query->where('branch_id', $branchId);
        }

        $inventory = $query->find($id);

        if (!$inventory) {
            return Helpers::result('Inventory item not found', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Inventory item retrieved successfully', Response::HTTP_OK, [
            'Inventory' => $inventory
        ]);
    }

    /**
     * Get inventory items by brand name.
     *
     * @param string $brandName
     * @return array
     */
    public function getInventoryByBrandName($brandName)
    {
        try {
            $user = auth()->user();
            $role = $user->roles->first()->name ?? 'N/A';
            $admin = $user->employee;

            $query = Inventory::query();
            // Check the role and filter accordingly
            if ($role === 'company admin') {
                $companyId = $admin->company_id;
                $query->whereHas('branch', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            } elseif ($role === 'branch admin') {
                $branchId = $admin->branch_id;
                $query->where('branch_id', $branchId);
            }

            $brands = Brand::where('name', 'LIKE', "%{$brandName}%")->get();

            if ($brands->isEmpty()) {
                return Helpers::result('Brand not found', Response::HTTP_NOT_FOUND);
            }

            $brandIds = $brands->pluck('id');
            $query->whereIn('brand_id', $brandIds);
            $inventory = $query->get();

            if ($inventory->isEmpty()) {
                return Helpers::result('No inventory items found for this brand', Response::HTTP_NOT_FOUND);
            }

            return Helpers::result('Inventory items retrieved successfully', Response::HTTP_OK, [
                'Inventory' => $inventory
            ]);
        } catch (\Throwable $e) {
            return Helpers::error($query, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get inventory items by category name.
     *
     * @param string $categoryName
     * @return array
     */
    public function getInventoryByCategoryName($categoryName)
    {
        $user = auth()->user();
        $role = $user->roles->first()->name ?? 'N/A';
        $admin = $user->employee;

        $query = Inventory::query();
        // Check the role and filter accordingly
        if ($role === 'company admin') {
            $companyId = $admin->company_id;
            $query->whereHas('branch', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        } elseif ($role === 'branch admin') {
            $branchId = $admin->branch_id;
            $query->where('branch_id', $branchId);
        }

        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return Helpers::result('Category not found', Response::HTTP_NOT_FOUND);
        }

        $query->where('category_id', $category->id);
        $inventory = $query->get();

        if ($inventory->isEmpty()) {
            return Helpers::result('No inventory items found for this category', Response::HTTP_NOT_FOUND);
        }

        return Helpers::result('Inventory items retrieved successfully', Response::HTTP_OK, [
            'Inventory' => $inventory
        ]);
    }

    public function storeInventory($request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $admin = $user->employee;
            $role = $user->roles->first()->name ?? 'N/A';

            // Check if the provided branch_id belongs to the user's company, except for admin
            $branchId = $request->branch_id ?? $admin->branch_id;
            if (!$branchId) {
                return Helpers::result('Branch ID is required', Response::HTTP_BAD_REQUEST);
            }

            if ($role !== 'admin' && $request->branch_id) {
                $branch = Branch::find($request->branch_id);
                if (!$branch || $branch->company_id != $admin->company_id) {
                    return Helpers::result('Invalid branch ID for the user\'s company', Response::HTTP_BAD_REQUEST);
                }
            }

            $inventoryDto = new InventoryCreateDTO($request, $branchId);
            $inventory = Inventory::create($inventoryDto->toArray());

            DB::commit();
            return Helpers::result('Inventory item stored successfully', Response::HTTP_CREATED, [
                'Inventory' => $inventory
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update an existing inventory item.
     *
     * @param int $id
     * @param $request
     * @return array
     */
    public function updateInventory($id, $request)
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return Helpers::result('Inventory item not found', Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        $admin = $user->employee;
        $role = $user->roles->first()->name ?? 'N/A';

        // Check if the provided branch_id belongs to the user's company, except for admin
        $branchId = $request->branch_id ?? $admin->branch_id;
        if (!$branchId) {
            return Helpers::result('Branch ID is required', Response::HTTP_BAD_REQUEST);
        }

        if ($role !== 'admin' && $request->branch_id) {
            $branch = Branch::find($request->branch_id);
            if (!$branch || $branch->company_id != $admin->company_id) {
                return Helpers::result('Invalid branch ID for the user\'s company', Response::HTTP_BAD_REQUEST);
            }
        }

        $inventoryDto = new InventoryCreateDTO($request, $branchId);
        $inventory->update($inventoryDto->toArray());

        return Helpers::result('Inventory item updated successfully', Response::HTTP_OK, [
            'Inventory' => $inventory
        ]);
    }

    /**
     * Delete an inventory item.
     *
     * @param int $id
     * @return array
     */
    public function deleteInventory($id)
    {
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return Helpers::result('Inventory item not found', Response::HTTP_NOT_FOUND);
        }

        $inventory->delete();

        return Helpers::result('Inventory item deleted successfully', Response::HTTP_OK);
    }
}
