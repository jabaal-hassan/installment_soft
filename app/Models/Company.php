<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    // Define the relationship between Company and Employee
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
