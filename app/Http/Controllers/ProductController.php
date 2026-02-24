<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk hapus file
use Illuminate\Support\Str; // Tambahkan ini untuk generate slug

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk dengan fitur Filter, Search, dan Sortir.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $productsQuery = Product::with('category');

        // 1. FILTER: Berdasarkan Slug Kategori
        if ($request->has('category') && $request->category != '') {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $productsQuery->where('category_id', $category->id);
        }
        
        // 2. SEARCH: Berdasarkan nama produk
        if ($request->has('search') && $request->search != '') {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. SORT: Berdasarkan harga atau terbaru
        switch ($request->sort) {
            case 'price_low':
                $productsQuery->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('base_price', 'desc');
                break;
            default:
                $productsQuery->latest();
                break;
        }

        $products = $productsQuery->paginate(12)->appends($request->query());

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Tampilkan detail produk dan ulasan.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variations']);
        $reviews = $product->reviews()->with('user')->latest()->paginate(5);

        $isInWishlist = false;
        if (Auth::check()) {
            $isInWishlist = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
        }

        // Perbaikan garis merah VS Code dengan Auth Facade
        $reviewedProductIds = Auth::check() 
            ? Auth::user()->reviews()->pluck('product_id') 
            : collect();

        return view('products.show', compact('product', 'reviews', 'reviewedProductIds', 'isInWishlist'));
    }

    /**
     * Simpan produk baru (Admin).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'base_price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Nama input 'image' sesuai form create
            'is_featured' => 'nullable|boolean',
        ]);

        // Generate Slug otomatis dari nama produk
        $data['slug'] = Str::slug($request->name) . '-' . time();

        // Simpan Gambar
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        // Kirim notifikasi sukses melayang
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk hardware baru berhasil ditambahkan ke katalog Pitocom!');
    }

    /**
     * Update data produk (Admin).
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'base_price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Perubahan pada produk ' . $product->name . ' berhasil disimpan.');
    }

    /**
     * Hapus produk (Admin).
     */
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk telah dihapus secara permanen dari sistem.');
    }
}