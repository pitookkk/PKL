<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PcBuilderController extends Controller
{
    /**
     * Display the PC Builder interface.
     */
    public function index()
    {
        // Define the standard building steps
        $steps = [
            ['id' => 'cpu', 'name' => 'Processor', 'slug' => 'processor'],
            ['id' => 'mobo', 'name' => 'Motherboard', 'slug' => 'motherboard'],
            ['id' => 'ram', 'name' => 'RAM', 'slug' => 'ram'],
            ['id' => 'gpu', 'name' => 'Graphics Card', 'slug' => 'graphic-cards'],
            ['id' => 'storage', 'name' => 'Storage', 'slug' => 'storage-solutions'],
            ['id' => 'psu', 'name' => 'Power Supply', 'slug' => 'power-supply'],
            ['id' => 'case', 'name' => 'Casing', 'slug' => 'case'],
        ];

        return view('pc-builder.index', compact('steps'));
    }

    /**
     * API: Get components for a specific category with compatibility filters.
     */
    public function getComponents(Request $request)
    {
        $categorySlug = $request->query('category');
        $socket = $request->query('socket');
        $ramType = $request->query('ram');

        $query = Product::whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });

        // Filter based on previous selections
        if ($socket && in_array($categorySlug, ['processor', 'motherboard'])) {
            $query->where('socket_type', $socket);
        }

        if ($ramType && in_array($categorySlug, ['motherboard', 'ram'])) {
            $query->where('ram_type', $ramType);
        }

        $products = $query->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => (float) $product->current_price,
                'image' => $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/150',
                'socket_type' => $product->socket_type,
                'ram_type' => $product->ram_type,
                'wattage' => $product->wattage_requirement,
            ];
        });

        return response()->json($products);
    }

    /**
     * Add all selected PC parts to the cart.
     */
    public function addAllToCart(Request $request)
    {
        $productIds = $request->input('products', []);
        
        if (empty($productIds)) {
            return response()->json(['success' => false, 'message' => 'No products selected.'], 400);
        }

        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->current_price,
                "image" => $product->image_path,
                "variation_name" => null,
            ];
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true, 'redirect' => route('cart.index')]);
    }
}
