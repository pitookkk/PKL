<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Bundle;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = [];
        $total = 0;

        if (Auth::check()) {
            // 1. Logged in user: Get from database
            $dbItems = CartItem::where('user_id', Auth::id())->with(['product', 'variation', 'bundle'])->get();
            foreach ($dbItems as $item) {
                if ($item->type === 'bundle' && $item->bundle) {
                    $cart['bundle-' . $item->bundle_id] = [
                        "name" => "[BUNDLE] " . $item->bundle->name,
                        "quantity" => $item->quantity,
                        "price" => (float) $item->bundle->price,
                        "image" => $item->bundle->image_path,
                        "variation_name" => null,
                        "type" => "bundle",
                        "bundle_id" => $item->bundle_id
                    ];
                    $total += $item->bundle->price * $item->quantity;
                } elseif ($item->product) {
                    $price = $item->product->current_price;
                    $variationName = null;
                    $key = (string) $item->product_id;

                    if ($item->product_variation_id && $item->variation) {
                        $price += $item->variation->additional_price;
                        $variationName = $item->variation->variation_name;
                        $key .= '-' . $item->product_variation_id;
                    }

                    $cart[$key] = [
                        "name" => $item->product->name,
                        "quantity" => $item->quantity,
                        "price" => (float) $price,
                        "image" => $item->product->image_path,
                        "variation_name" => $variationName,
                        "type" => "product",
                        "product_id" => $item->product_id,
                        "variation_id" => $item->product_variation_id
                    ];
                    $total += $price * $item->quantity;
                }
            }
        } else {
            // 2. Guest user: Get from session
            $cart = session()->get('cart', []);
            foreach ($cart as $details) {
                $total += $details['price'] * $details['quantity'];
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add item to cart (Supports Product and Bundle).
     */
    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variation_id' => 'nullable|exists:product_variations,id',
            'type' => 'nullable|string|in:product,bundle'
        ]);

        $type = $request->input('type', 'product');
        $qty = $request->quantity;

        if (Auth::check()) {
            // --- DATABASE LOGIC ---
            if ($type === 'bundle') {
                $bundle = Bundle::findOrFail($id);
                $item = CartItem::firstOrNew([
                    'user_id' => Auth::id(),
                    'bundle_id' => $id,
                    'type' => 'bundle'
                ]);
                $item->quantity += $item->exists ? $qty : ($qty - 1);
                $item->save();
            } else {
                $product = Product::findOrFail($id);
                $item = CartItem::firstOrNew([
                    'user_id' => Auth::id(),
                    'product_id' => $id,
                    'product_variation_id' => $request->variation_id,
                    'type' => 'product'
                ]);
                $item->quantity += $item->exists ? $qty : ($qty - 1);
                $item->save();
            }
        } else {
            // --- SESSION LOGIC (Fallback for guests) ---
            $cart = session()->get('cart', []);
            if ($type === 'bundle') {
                $bundle = Bundle::findOrFail($id);
                $key = 'bundle-' . $id;
                if (isset($cart[$key])) {
                    $cart[$key]['quantity'] += $qty;
                } else {
                    $cart[$key] = [
                        "name" => "[BUNDLE] " . $bundle->name, 
                        "quantity" => $qty, 
                        "price" => (float)$bundle->price, // Price set here
                        "image" => $bundle->image_path, 
                        "type" => "bundle", 
                        "bundle_id" => $id
                    ];
                }
            } else {
                $product = Product::findOrFail($id);
                $key = $request->variation_id ? $id . '-' . $request->variation_id : (string)$id;
                
                // Calculate correct price for session
                $price = (float)$product->current_price;
                $vName = null;
                if ($request->variation_id) {
                    $v = ProductVariation::find($request->variation_id);
                    $price += (float)$v->additional_price;
                    $vName = $v->variation_name;
                }

                if (isset($cart[$key])) {
                    $cart[$key]['quantity'] += $qty;
                } else {
                    $cart[$key] = [
                        "name" => $product->name, 
                        "quantity" => $qty, 
                        "price" => $price, // Price set here
                        "image" => $product->image_path, 
                        "variation_name" => $vName, 
                        "type" => "product", 
                        "product_id" => $id
                    ];
                }
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Barang ditambahkan ke keranjang!');
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if (Auth::check()) {
            // Database update
            // Note: $cartItemId here needs to be handled carefully if it's string key
            if (str_starts_with($cartItemId, 'bundle-')) {
                $id = str_replace('bundle-', '', $cartItemId);
                CartItem::where('user_id', Auth::id())->where('bundle_id', $id)->update(['quantity' => $request->quantity]);
            } else {
                $ids = explode('-', $cartItemId);
                $query = CartItem::where('user_id', Auth::id())->where('product_id', $ids[0]);
                if (isset($ids[1])) $query->where('product_variation_id', $ids[1]);
                $query->update(['quantity' => $request->quantity]);
            }
        } else {
            // Session update
            $cart = session()->get('cart');
            if (isset($cart[$cartItemId])) {
                $cart[$cartItemId]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui.');
    }
    
    /**
     * Remove item from cart.
     */
    public function remove($cartItemId)
    {
        if (Auth::check()) {
            if (str_starts_with($cartItemId, 'bundle-')) {
                $id = str_replace('bundle-', '', $cartItemId);
                CartItem::where('user_id', Auth::id())->where('bundle_id', $id)->delete();
            } else {
                $ids = explode('-', $cartItemId);
                $query = CartItem::where('user_id', Auth::id())->where('product_id', $ids[0]);
                if (isset($ids[1])) $query->where('product_variation_id', $ids[1]);
                $query->delete();
            }
        } else {
            $cart = session()->get('cart');
            if (isset($cart[$cartItemId])) {
                unset($cart[$cartItemId]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Barang dihapus dari keranjang.');
    }
}
