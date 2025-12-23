<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Complaint;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'buyer_id',
        'total_amount',
        'status',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
