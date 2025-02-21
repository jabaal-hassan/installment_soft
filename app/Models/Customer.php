<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function customerAccounts()
    {
        return $this->hasMany(CustomerAccount::class);
    }

    public function installmentTables()
    {
        return $this->hasMany(InstallmentTable::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
