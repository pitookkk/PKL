<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = Bundle::withCount('products')->latest()->paginate(10);
        return view('admin.bundles.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.bundles.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('bundles', 'public');
            }

            $bundle = Bundle::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image_path' => $imagePath,
                'is_active' => true,
            ]);

            foreach ($request->products as $productData) {
                $bundle->products()->attach($productData['id'], ['quantity' => $productData['quantity']]);
            }

            return redirect()->route('admin.bundles.index')->with('success', 'Bundle package created successfully.');
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bundle $bundle)
    {
        $bundle->load('products');
        $products = Product::orderBy('name')->get();
        return view('admin.bundles.edit', compact('bundle', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $bundle) {
            if ($request->hasFile('image')) {
                if ($bundle->image_path) {
                    Storage::disk('public')->delete($bundle->image_path);
                }
                $bundle->image_path = $request->file('image')->store('bundles', 'public');
            }

            $bundle->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => $request->has('is_active'),
            ]);

            // Sync products: remove all and re-attach
            $bundle->products()->detach();
            foreach ($request->products as $productData) {
                $bundle->products()->attach($productData['id'], ['quantity' => $productData['quantity']]);
            }

            return redirect()->route('admin.bundles.index')->with('success', 'Bundle package updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bundle $bundle)
    {
        if ($bundle->image_path) {
            Storage::disk('public')->delete($bundle->image_path);
        }
        $bundle->delete();
        return redirect()->route('admin.bundles.index')->with('success', 'Bundle package deleted.');
    }
}
