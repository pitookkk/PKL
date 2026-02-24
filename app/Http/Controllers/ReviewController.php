<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'completed')
                      ->first();

        // 1. Validate if the order belongs to the user and is completed
        if (!$order) {
            return back()->with('error', 'You can only review products from your completed orders.');
        }

        // 2. Validate if the product is actually in the order
        if (!$order->items()->where('product_id', $request->product_id)->exists()) {
            return back()->with('error', 'This product was not part of the specified order.');
        }

        // 3. Validate if the user has already reviewed this product for this order
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $request->product_id)
                                ->where('order_id', $request->order_id)
                                ->exists();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            // The prompt suggests using a library for compression. For now, we'll just store it.
            $photoPath = $request->file('photo')->store('reviews', 'public');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Your review has been submitted successfully!');
    }
}
