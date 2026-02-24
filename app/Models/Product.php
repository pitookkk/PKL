<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'brand',
        'category_id',
        'base_price',
        'flash_sale_price',
        'flash_sale_start',
        'flash_sale_end',
        'stock',
        'image_path',
        'is_featured',
        'socket_type',
        'ram_type',
        'wattage_requirement',
        'specifications',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'flash_sale_start' => 'datetime',
        'flash_sale_end' => 'datetime',
        'base_price' => 'decimal:2',
        'flash_sale_price' => 'decimal:2',
        'specifications' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['current_price', 'is_flash_sale_active'];


    /**
     * Determine if the product is currently in a flash sale.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getIsFlashSaleActiveAttribute(): bool
    {
        return $this->flash_sale_price !== null &&
            $this->flash_sale_start &&
            $this->flash_sale_end &&
            Carbon::now()->between($this->flash_sale_start, $this->flash_sale_end);
    }

    /**
     * Get the current price of the product, considering flash sales.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->is_flash_sale_active ? $this->flash_sale_price : $this->base_price;
    }

    /**
     * Scope a query to only include active flash sale products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveFlashSale($query)
    {
        return $query->whereNotNull('flash_sale_price')
            ->whereNotNull('flash_sale_start')
            ->whereNotNull('flash_sale_end')
            ->where('flash_sale_start', '<=', Carbon::now())
            ->where('flash_sale_end', '>=', Carbon::now());
    }


    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the variations for the product.
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all wishlist entries for the product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the bundles that include this product.
     */
    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_product');
    }
}
