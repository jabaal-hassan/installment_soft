<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryOfficer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function recoveryOfficer()
    {
        return $this->belongsTo(Employee::class);
    }
}
