<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function customerAccounts()
    {
        return $this->hasMany(customerAccount::class);
    }
}
