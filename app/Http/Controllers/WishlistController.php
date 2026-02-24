<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlists()->with('product.category')->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Add a product to the user's wishlist.
     */
    public function add(Product $product)
    {
        Auth::user()->wishlists()->firstOrCreate([
            'product_id' => $product->id,
        ]);

        return back()->with('success', "'{$product->name}' has been added to your wishlist!");
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public function remove(Product $product)
    {
        Auth::user()->wishlists()->where('product_id', $product->id)->delete();

        return back()->with('success', "'{$product->name}' has been removed from your wishlist.");
    }
}
