<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_name',
        'sku',
        'additional_price',
        'stock',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order items for the variation.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
