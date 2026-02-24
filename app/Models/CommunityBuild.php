<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityBuild extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'status',
        'likes_count',
    ];

    /**
     * Get the user who posted the build.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products used in this build.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'build_product');
    }
}
