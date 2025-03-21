<?php

namespace App\DTOs\CustomerDTOs;

use App\DTOs\BaseDTOs;
use App\Models\Employee;
use App\Models\InquiryOfficer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerCreateDTO extends BaseDTOs
{
    public string $name;
    public string $father_name;
    public int $sell_officer_id;
    public ?int $inquiry_officer_id;
    public ?int $branch_id;
    public string $phone_number;
    public string $cnic;
    public string $address;
    public string $office_address;
    public string $employment_type;
    public ?string $company_name;
    public int $years_of_experience;
    public string $cnic_Front_image;
    public string $cnic_Back_image;
    public ?string $customer_image;
    public ?string $check_image;
    public ?string $video;
    public string $status;

    public function __construct($request, $employee)
    {
        $this->name = $request->name;
        $this->father_name = $request->father_name;
        $this->sell_officer_id = $employee->id;
        $this->branch_id = $request->branch_id ?? $employee->branch_id;
        $this->phone_number = $request->phone_number;
        $this->cnic = $request->cnic;
        $this->address = $request->address;
        $this->office_address = $request->office_address;
        $this->employment_type = $request->employment_type ?? 'company';
        $this->company_name = $request->company_name;
        $this->years_of_experience = $request->years_of_experience ?? 0;
        $this->cnic_Front_image = $this->handleFileUpload($request, 'cnic_Front_image', 'customers/cnic_images');
        $this->cnic_Back_image = $this->handleFileUpload($request, 'cnic_Back_image', 'customers/cnic_images');
        $this->customer_image = $this->handleFileUpload($request, 'customer_image', 'customers/customer_images');
        $this->check_image = $this->handleFileUpload($request, 'check_image', 'customers/check_images');
        $this->video = $this->handleFileUpload($request, 'video', 'customers/videos');
        $this->status = $request->status ?? 'processing';

        $inquiryOfficer = InquiryOfficer::inRandomOrder()->first();
        if ($inquiryOfficer) {
            $this->inquiry_officer_id = $inquiryOfficer->inquiry_officer_id;
        } else {
            $branchAdmin = Employee::where('branch_id', $employee->branch_id)->whereHas('user.roles', function ($query) {
                $query->where('name', 'admin');
            })->first();
            $this->inquiry_officer_id = $branchAdmin ? $branchAdmin->id : $employee->id;
        }
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
