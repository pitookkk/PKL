<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'device_name',
        'specifications',
        'purchase_date',
        'warranty_expiry',
    ];

    protected $casts = [
        'specifications' => 'array',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }
}
