<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_asset_id',
        'last_service',
        'next_service_reminder',
    ];

    protected $casts = [
        'last_service' => 'date',
        'next_service_reminder' => 'date',
    ];

    public function customerAsset()
    {
        return $this->belongsTo(CustomerAsset::class);
    }
}
