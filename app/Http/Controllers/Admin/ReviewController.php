<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user', 'product')->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // The ReviewObserver will handle recalculating product stats
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review has been deleted.');
    }
}
