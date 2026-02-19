<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'base_price' => $request->base_price,
            'stock' => $request->stock,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
        ]);

        // Handle variations if any
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                if (!empty($variation['name'])) {
                    $product->variations()->create([
                        'variation_name' => $variation['name'],
                        'sku' => $variation['sku'] ?? null,
                        'additional_price' => $variation['price'] ?? 0,
                        'stock' => $variation['stock'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit(Product $product)
    {
        $product->load('variations');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'base_price' => $request->base_price,
            'stock' => $request->stock,
            'is_featured' => $request->has('is_featured'),
        ]);

        // Handle variations
        $product->variations()->delete();
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                if (!empty($variation['name'])) {
                    $product->variations()->create([
                        'variation_name' => $variation['name'],
                        'sku' => $variation['sku'] ?? null,
                        'additional_price' => $variation['price'] ?? 0,
                        'stock' => $variation['stock'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
