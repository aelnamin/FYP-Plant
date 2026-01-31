<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;


    protected $table = 'returns';


    protected $fillable = [
        'order_id',
        'order_item_id',
        'buyer_id',
        'seller_id',
        'reason',
        'request_type',
        'image',
        'status',
        'seller_note',
    ];

    /**
     * Default attribute values
     */
    protected $attributes = [
        'status' => 'pending', // new requests default to pending
    ];

    /**
     * Casts
     */
    protected $casts = [
        'order_id' => 'integer',
        'buyer_id' => 'integer',
        'seller_id' => 'integer',
        'reason' => 'string',
        'image' => 'string',
        'status' => 'string',
    ];

    /**
     * Relationships
     */

    // The order this return request belongs to
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }


    // The buyer who requested the return
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // The seller responsible for this return
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by seller
     */
    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /**
     * Scope to filter by buyer
     */
    public function scopeForBuyer($query, $buyerId)
    {
        return $query->where('buyer_id', $buyerId);
    }
}