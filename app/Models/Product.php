<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'category_id',
        'product_name',
        'description',
        'variants',
        'price',
        'stock_quantity',
        'approval_status',
        'sunlight_requirement',
        'watering_frequency',
        'difficulty_level',
        'growth_stage',
        'approved_by',

    ];

    protected $casts = [
        'variants' => 'array', // Laravel automatically decodes JSON to array
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
