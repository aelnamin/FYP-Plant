<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function handledComplaints()
    {
        return $this->hasMany(Complaint::class, 'handled_by');
    }
}
