<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductGrowthLog;
use App\Models\ProductCareLog;
use App\Models\ProductImage;
use App\Models\Seller;


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
        'health_condition',

    ];

    protected $casts = [
        'variants' => 'array',
    ];

    /**
     * Select only the data needed by storefront product cards.
     *
     * Using scalar subqueries avoids loading every image and a separate seller
     * relationship for cards that only render one image and one seller name.
     */
    public function scopeForStorefrontCards($query)
    {
        return $query
            ->select([
                'products.id',
                'products.product_name',
                'products.price',
                'products.created_at',
            ])
            ->addSelect([
                'image_path' => ProductImage::query()
                    ->select('image_path')
                    ->whereColumn('product_images.product_id', 'products.id')
                    ->orderBy('product_images.id')
                    ->limit(1),
                'seller_business_name' => Seller::query()
                    ->select('business_name')
                    ->whereColumn('sellers.id', 'products.seller_id')
                    ->limit(1),
            ]);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
    public function growthLogs()
    {
        return $this->hasMany(ProductGrowthLog::class);
    }

    public function careLogs()
    {
        return $this->hasMany(ProductCareLog::class);
    }

}
