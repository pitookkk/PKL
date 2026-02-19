<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('welcome')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        DB::beginTransaction();
        try {
            // Create the main order record
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'pending', // Default status
                'order_date' => now(),
            ]);

            // Create order items from the cart
            foreach($cart as $cartItemId => $details) {
                // Split the composite key 'product_id-variation_id'
                $ids = explode('-', $cartItemId);
                $productId = $ids[0];
                $variationId = $ids[1] ?? null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_variation_id' => $variationId,
                    'quantity' => $details['quantity'],
                    'price_at_purchase' => $details['price'],
                ]);

                // TODO: Decrement stock from products/product_variations table
            }

            DB::commit();

            // Clear the cart from the session
            session()->forget('cart');

            return redirect()->route('dashboard')->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error message
            // Log::error($e->getMessage());
            return redirect()->route('cart.index')->with('error', 'An error occurred while placing your order. Please try again.');
        }
    }
}
