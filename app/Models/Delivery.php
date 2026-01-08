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
        'courier_name',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * A delivery belongs to an order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
