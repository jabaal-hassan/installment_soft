<?php

namespace App\DTOs\CustomerDTOs;

use App\DTOs\BaseDTOs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuarantorCreateDTO extends BaseDTOs
{
    public string $customer_id;
    public string $name;
    public string $father_name;
    public string $cnic;
    public string $phone_number;
    public string $relationship;
    public string $address;
    public string $office_address;
    public string $employment_type;
    public string $company_name;
    public string $years_of_experience;
    public ?string $cnic_Front_image;
    public ?string $cnic_Back_image;
    public ?string $video;

    public function __construct($request)
    {
        $this->customer_id = $request->customer_id;
        $this->name = $request->name;
        $this->father_name = $request->father_name;
        $this->cnic = $request->cnic;
        $this->phone_number = $request->phone_number;
        $this->relationship = $request->relationship;
        $this->address = $request->address;
        $this->office_address = $request->office_address;
        $this->years_of_experience = $request->years_of_experience;
        $this->company_name = $request->company_name;
        $this->employment_type = $request->employment_type;
        $this->cnic_Front_image = $this->handleFileUpload($request, 'cnic_Front_image', 'customers/cnic_images');
        $this->cnic_Back_image = $this->handleFileUpload($request, 'cnic_Back_image', 'customers/cnic_images');
        $this->video = $this->handleFileUpload($request, 'video', 'customers/videos');
    }
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
