<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variation_id' => 'nullable|exists:product_variations,id'
        ]);

        $cart = session()->get('cart', []);
        $variation = null;
        $price = $product->base_price;
        $stock = $product->stock;
        
        $cartItemId = $product->id; // Default cart item ID is product ID

        // If there's a variation, adjust price, stock, and cart item ID
        if ($request->variation_id) {
            $variation = ProductVariation::find($request->variation_id);
            if ($variation && $variation->product_id == $product->id) {
                $price += $variation->additional_price;
                $stock = $variation->stock;
                $cartItemId = $product->id . '-' . $variation->id; // Create a unique ID for product-variation combo
            } else {
                 return redirect()->back()->with('error', 'Invalid product variation.');
            }
        }
        
        // Check stock
        if ($stock < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // If cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $cartItemId => [
                    "name" => $product->name,
                    "quantity" => $request->quantity,
                    "price" => $price,
                    "image" => $product->image_path,
                    "variation_name" => $variation ? $variation->variation_name : null,
                ]
            ];
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
        }

        // If cart not empty then check if this product exist then increment quantity
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $request->quantity;
        } else {
            // If item not exist in cart then add to cart with quantity
            $cart[$cartItemId] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $price,
                "image" => $product->image_path,
                "variation_name" => $variation ? $variation->variation_name : null,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart');
        if (isset($cart[$cartItemId])) {
            // TODO: Check for stock availability before updating
            $cart[$cartItemId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
        }
        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }
    
    public function remove($cartItemId)
    {
        $cart = session()->get('cart');
        if (isset($cart[$cartItemId])) {
            unset($cart[$cartItemId]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Product removed successfully.');
        }
        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }
}
