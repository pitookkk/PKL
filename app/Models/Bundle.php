<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'is_active',
    ];

    /**
     * Get the products included in this bundle.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'bundle_product')
                    ->withPivot('quantity');
    }

    /**
     * Boot the model to handle slug generation.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($bundle) {
            $bundle->slug = Str::slug($bundle->name) . '-' . time();
        });
    }
}
