<?php

namespace App\DTOs\EmployeeDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class EmployeeCreateDTO extends BaseDTOs
{
    public int $user_id;
    public int $company_id;
    public ?int $branch_id;
    public string $name;
    public string $father_name;
    public string $phone_number;
    public string $id_card_number;
    public string $address;
    public string $position;
    public string $date_of_joining;
    public int $pay;
    public ?string $id_card_image;  // Nullable
    public ?string $check_image;    // Nullable

    /**
     * Construct the EmployeeCreateDTO with the input request.
     *
     * @param Request $request
     * @param int $userId
     * @param int $companyId
     * @param ?int $branchId
     */
    public function __construct(Request $request, int $userId, int $companyId, ?int $branchId)
    {
        $this->user_id = $userId;
        $this->company_id = $companyId;
        $this->branch_id = $branchId;
        $this->name = $request->input('name');
        $this->father_name = $request->input('father_name');
        $this->phone_number = $request->input('phone_number');
        $this->id_card_number = $request->input('id_card_number');
        $this->address = $request->input('address');
        $this->position = $request->input('position');
        $this->date_of_joining = $request->input('date_of_joining', now()->toDateString());
        $this->pay = $request->input('pay');

        // Handle file uploads
        $this->id_card_image = $this->handleFileUpload($request, 'id_card_image', 'id_cards');
        $this->check_image = $this->handleFileUpload($request, 'check_image', 'check_images');
    }

    /**
     * Handle file upload logic.
     *
     * @param Request $request
     * @param string $field
     * @param string $folder
     * @return string|null
     */
    private function handleFileUpload(Request $request, $field, $folder)
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $timestamp = now()->format('YmdHs');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

            $file->storeAs($folder, $newFileName, 'public');
            return $folder . '/' . $newFileName;
        }

        return null; // Return null if no file is uploaded
    }
}
