<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function receivedInstallments()
    {
        return $this->hasMany(InstallmentTable::class, 'recived_officer_id');
    }
    public function sales()
    {
        return $this->hasMany(Sale::class, 'sell_officer_id');
    }
}
