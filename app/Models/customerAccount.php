<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerAccount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function installmentTables()
    {
        return $this->hasMany(InstallmentTable::class);
    }
}
