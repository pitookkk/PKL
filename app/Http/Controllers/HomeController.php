<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with featured products and categories.
     */
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->withCount('products')
            ->take(6)
            ->get();

        return view('welcome', compact('featuredProducts', 'categories'));
    }
}
