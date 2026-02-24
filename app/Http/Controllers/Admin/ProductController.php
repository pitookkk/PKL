<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'brand' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'socket_type' => 'nullable|string|max:50',
            'ram_type' => 'nullable|string|max:50',
            'wattage_requirement' => 'nullable|integer|min:0',
            'specifications' => 'nullable|array',
            'variations' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($request) {
            $imagePath = $request->file('image')->store('products', 'public');

            // Process dynamic specifications
            $specs = [];
            if ($request->has('specifications')) {
                foreach ($request->specifications as $spec) {
                    if (!empty($spec['label'])) {
                        $specs[$spec['label']] = $spec['value'];
                    }
                }
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
                'socket_type' => $request->socket_type,
                'ram_type' => $request->ram_type,
                'wattage_requirement' => $request->wattage_requirement ?? 0,
                'specifications' => $specs,
            ]);

            // Handle variations
            if ($request->has('variations')) {
                foreach ($request->variations as $v) {
                    if (!empty($v['name'])) {
                        $product->variations()->create([
                            'variation_name' => $v['name'],
                            'sku' => $v['sku'] ?? null,
                            'additional_price' => $v['price'] ?? 0,
                            'stock' => $v['stock'] ?? 0,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product and variations created successfully.');
        });
    }

    public function edit(Product $product)
    {
        $product->load('variations');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

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
            'specifications' => 'nullable|array',
            'variations' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($request, $product) {
            if ($request->hasFile('image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $product->image_path = $request->file('image')->store('products', 'public');
            }

            $specs = [];
            if ($request->has('specifications')) {
                foreach ($request->specifications as $spec) {
                    if (!empty($spec['label'])) {
                        $specs[$spec['label']] = $spec['value'];
                    }
                }
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'brand' => $request->brand,
                'category_id' => $request->category_id,
                'base_price' => $request->base_price,
                'stock' => $request->stock,
                'is_featured' => $request->has('is_featured'),
                'socket_type' => $request->socket_type,
                'ram_type' => $request->ram_type,
                'wattage_requirement' => $request->wattage_requirement ?? 0,
                'specifications' => $specs,
            ]);

            // Simple sync: Delete and Re-create variations
            $product->variations()->delete();
            if ($request->has('variations')) {
                foreach ($request->variations as $v) {
                    if (!empty($v['name'])) {
                        $product->variations()->create([
                            'variation_name' => $v['name'],
                            'sku' => $v['sku'] ?? null,
                            'additional_price' => $v['price'] ?? 0,
                            'stock' => $v['stock'] ?? 0,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        });
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
