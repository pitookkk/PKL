<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variation_id',
        'bundle_id',
        'quantity',
        'type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
