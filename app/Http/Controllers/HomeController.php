<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the welcome page with featured products, bundles, and categories.
     */
    public function index()
    {
        // 1. Featured Products
        $featuredProducts = Product::where('is_featured', true)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(8)
            ->get();

        // 2. Flash Sale Products (Using scope)
        $flashSaleProducts = Product::activeFlashSale()
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        // 3. Active Bundles
        $bundles = Bundle::where('is_active', true)
            ->with('products')
            ->latest()
            ->take(4)
            ->get();

        // 4. Categories
        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->take(6)
            ->get();

        return view('welcome', compact('featuredProducts', 'flashSaleProducts', 'bundles', 'categories'));
    }
}
