<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $primaryKey = 'complaint_id';

    protected $fillable = [
        'user_id',
        'seller_id',
        'order_id',
        'handled_by',
        'complaint_message',
        'admin_response',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'handled_by');
    }
}
