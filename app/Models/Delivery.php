<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seller_id',
        'courier_name',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
    ];

    // Cast the datetime fields
    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    // In Delivery.php
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

}
