<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with('items.product') // Eager load products in order items
            ->latest()
            ->paginate(10);
        
        // Get a list of products the user has already reviewed
        $reviewedProductIds = $user->reviews()->pluck('product_id')->unique();

        return view('dashboard.index', compact('user', 'orders', 'reviewedProductIds'));
    }
}
