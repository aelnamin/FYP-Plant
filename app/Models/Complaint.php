<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $primaryKey = 'complaint_id'; // <-- important
    public $incrementing = true;            // auto-increment
    protected $keyType = 'int';             // integer primary key

    protected $fillable = [
        'user_id',
        'seller_id',
        'order_id',
        'handled_by',
        'complaint_message',
        'admin_response',
        'status',
        'problem_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    // Problem type constants
    const PROBLEM_MISSING_PARCEL = 'missing_parcel';
    const PROBLEM_DAMAGED_ITEM = 'damaged_item';
    const PROBLEM_WRONG_ITEM = 'wrong_item';
    const PROBLEM_LATE_DELIVERY = 'late_delivery';
    const PROBLEM_QUALITY_ISSUE = 'quality_issue';
    const PROBLEM_SELLER_BEHAVIOR = 'seller_behavior';
    const PROBLEM_REFUND_ISSUE = 'refund_issue';
    const PROBLEM_OTHER = 'other';

    public static function getProblemTypes(): array
    {
        return [
            self::PROBLEM_MISSING_PARCEL => 'Missing Parcel / Not Received',
            self::PROBLEM_DAMAGED_ITEM => 'Damaged Item',
            self::PROBLEM_WRONG_ITEM => 'Wrong Item Received',
            self::PROBLEM_LATE_DELIVERY => 'Late Delivery',
            self::PROBLEM_QUALITY_ISSUE => 'Quality Issue',
            self::PROBLEM_SELLER_BEHAVIOR => 'Seller Behavior / Communication',
            self::PROBLEM_REFUND_ISSUE => 'Refund / Payment Issue',
            self::PROBLEM_OTHER => 'Other',
        ];
    }

    public function getProblemTypeLabel(): string
    {
        return self::getProblemTypes()[$this->problem_type] ?? 'Unknown';
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
