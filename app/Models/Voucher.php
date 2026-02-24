<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Check if voucher is valid for use.
     */
    public function isValid($totalAmount)
    {
        if (!$this->is_active) return false;
        
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        
        if ($totalAmount < $this->min_spend) return false;

        return true;
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount($totalAmount)
    {
        if ($this->type === 'percent') {
            return ($this->value / 100) * $totalAmount;
        }

        return $this->value;
    }
}
