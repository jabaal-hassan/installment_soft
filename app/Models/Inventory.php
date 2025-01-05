<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the branch that owns the inventory.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
