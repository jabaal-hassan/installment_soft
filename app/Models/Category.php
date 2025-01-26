<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
