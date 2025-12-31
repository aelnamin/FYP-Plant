<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGrowthLog extends Model
{
    protected $fillable = [
        'product_id',
        'seller_id',
        'height_cm',
        'leaf_count',
        'growth_stage',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
