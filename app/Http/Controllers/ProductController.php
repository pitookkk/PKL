<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products, optionally filtered by category.
     */
    public function index(Request $request)
    {
        $productsQuery = Product::with('category')->latest();

        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $productsQuery->where('category_id', $category->id);
        }
        
        if ($request->has('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $productsQuery->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category', 'variations');
        return view('products.show', compact('product'));
    }
}
