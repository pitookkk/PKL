<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlashSaleController extends Controller
{
    /**
     * Display the flash sale management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Order by whether the flash sale end date is null, then by the date itself.
        // This puts active flash sales (non-null dates) first in descending order.
        $products = Product::orderByRaw('flash_sale_end IS NULL ASC, flash_sale_end DESC')
            ->orderBy('name')
            ->paginate(20);
        return view('admin.flash-sales.index', compact('products'));
    }

    /**
     * Add or update a product in the flash sale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'flash_sale_price' => 'required|numeric|min:0|lt:'.$product->base_price,
            'flash_sale_start' => 'required|date|after_or_equal:' . \Carbon\Carbon::now()->subMinute()->format('Y-m-d H:i:s'),
            'flash_sale_end' => 'required|date|after:flash_sale_start',
        ], [
            'flash_sale_price.lt' => 'The flash sale price must be less than the base price ('.$product->base_price.').',
        ]);

        $product->update([
            'flash_sale_price' => $request->flash_sale_price,
            'flash_sale_start' => Carbon::parse($request->flash_sale_start),
            'flash_sale_end' => Carbon::parse($request->flash_sale_end),
        ]);

        return redirect()->route('admin.flash-sales.index')->with('success', "Flash sale for '{$product->name}' has been successfully set.");
    }

    /**
     * Remove a product from the flash sale.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->update([
            'flash_sale_price' => null,
            'flash_sale_start' => null,
            'flash_sale_end' => null,
        ]);

        return redirect()->route('admin.flash-sales.index')->with('success', "Flash sale for '{$product->name}' has been removed.");
    }
}
