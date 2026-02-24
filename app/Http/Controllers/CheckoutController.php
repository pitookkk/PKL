<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Bundle;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('welcome')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city_name' => 'required|string',
            'courier' => 'required|string',
            'shipping_cost' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsToCreate = [];
            $stockReductions = [];

            // ... (logika item keranjang tetap sama) ...

            // 2. Handle Voucher Discount
            $discountAmount = 0;
            $voucherId = null;
            if (session()->has('applied_voucher')) {
                $vData = session('applied_voucher');
                $voucher = Voucher::find($vData['id']);
                
                // Re-validate to be safe
                if ($voucher && $voucher->isValid($subtotal)) {
                    $discountAmount = $voucher->calculateDiscount($subtotal);
                    $voucherId = $voucher->id;
                    $voucher->increment('used_count');
                }
            }

            $finalTotal = ($subtotal - $discountAmount);

            // 3. Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $finalTotal,
                'address' => "Penerima: {$request->recipient_name} ({$request->phone_number}) | Alamat: {$request->address}, {$request->city_name}",
                'province_id' => 0,
                'city_id' => 0,
                'courier' => $request->courier,
                'shipping_method' => 'Standard',
                'shipping_cost' => $request->shipping_cost,
                'status' => 'completed',
                'order_date' => now(),
            ]);

            // 4. Create Order Items
            foreach($orderItemsToCreate as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'product_variation_id' => $itemData['product_variation_id'] ?? null,
                    'quantity' => $itemData['qty'],
                    'price_at_purchase' => $itemData['price'],
                ]);
            }

            // 5. Reduce Stocks
            foreach ($stockReductions as $reduction) {
                $reduction['model']->decrement('stock', $reduction['qty']);
            }

            DB::commit();
            session()->forget(['cart', 'applied_voucher']);

            return redirect()->route('dashboard')->with('success', 'Order completed successfully with voucher discount!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Bundle Checkout failed: " . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
