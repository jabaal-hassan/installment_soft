<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the inventory items for the brand.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
}
