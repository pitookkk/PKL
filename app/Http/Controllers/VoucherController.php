<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Apply voucher to the current cart.
     */
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (!$voucher) {
            return back()->with('error', 'Invalid voucher code.');
        }

        // Calculate current cart total to check min_spend
        $totalAmount = 0;
        if (Auth::check()) {
            $items = CartItem::where('user_id', Auth::id())->with(['product', 'bundle'])->get();
            foreach ($items as $item) {
                $price = $item->type === 'bundle' ? $item->bundle->price : $item->product->current_price;
                if ($item->variation) $price += $item->variation->additional_price;
                $totalAmount += $price * $item->quantity;
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($cart as $details) {
                $totalAmount += $details['price'] * $details['quantity'];
            }
        }

        if (!$voucher->isValid($totalAmount)) {
            return back()->with('error', 'Voucher is either expired, usage limit reached, or minimum spend not met.');
        }

        // Store voucher info in session
        session()->put('applied_voucher', [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'discount_amount' => $voucher->calculateDiscount($totalAmount)
        ]);

        return back()->with('success', 'Voucher applied successfully!');
    }

    /**
     * Remove the currently applied voucher.
     */
    public function remove()
    {
        session()->forget('applied_voucher');
        return back()->with('success', 'Voucher removed.');
    }
}
