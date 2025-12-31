<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCareLog extends Model
{
    protected $fillable = [
        'product_id',
        'seller_id',
        'care_type',
        'notes',
        'care_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
