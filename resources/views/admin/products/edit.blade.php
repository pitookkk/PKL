@extends('layouts.app')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
@include('admin.partials.nav')

<div class="max-w-6xl mx-auto px-4 py-10">
    {{-- Breadcrumb & Title --}}
    <div class="mb-10 animate-fade-in-up">
        <nav class="flex mb-4 text-sm font-medium text-slate-400">
            <a href="{{ route('admin.products.index') }}" class="hover:text-sky-500 transition">Products</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">Edit Product</span>
        </nav>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center">
            <span class="bg-sky-500/10 text-sky-500 p-2 rounded-xl mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </span>
            Edit: {{ $product->name }}
        </h1>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
          x-data="productForm({{ json_encode($product->variations) }})" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-left">
            {{-- Kolom Utama --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Informasi Dasar</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Produk</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}"
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk</label>
                            <textarea name="description" id="description" rows="6" required
                                      class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="base_price" class="block text-sm font-bold text-slate-700 mb-2">Harga Dasar (Rp)</label>
                                <input type="number" name="base_price" id="base_price" required value="{{ old('base_price', $product->base_price) }}"
                                       class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-sky-600">
                            </div>
                            <div>
                                <label for="stock" class="block text-sm font-bold text-slate-700 mb-2">Stok Utama</label>
                                <input type="number" name="stock" id="stock" required value="{{ old('stock', $product->stock) }}"
                                       class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Variations Section --}}
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Variasi Produk</h3>
                            <p class="text-xs text-slate-400 mt-1 italic">* Variasi lama akan diperbarui sesuai input di bawah</p>
                        </div>
                        <button type="button" @click="addVariation()" 
                                class="px-4 py-2 bg-sky-50 text-sky-600 rounded-xl font-bold text-xs hover:bg-sky-100 transition-colors">
                            + Tambah Variasi
                        </button>
                    </div>

                    <div class="space-y-4" id="variations-container">
                        <template x-for="(variation, index) in variations" :key="index">
                            <div class="grid grid-cols-12 gap-3 p-4 bg-slate-50 rounded-[1.5rem] relative group animate-fade-in-up">
                                <div class="col-span-4">
                                    <input type="text" x-model="variation.variation_name" :name="`variations[${index}][name]`" placeholder="Nama (e.g. RAM 16GB)" 
                                           class="w-full px-4 py-2 bg-white border-none rounded-xl text-sm font-semibold focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="col-span-3">
                                    <input type="text" x-model="variation.sku" :name="`variations[${index}][sku]`" placeholder="SKU" 
                                           class="w-full px-4 py-2 bg-white border-none rounded-xl text-sm focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" x-model="variation.additional_price" :name="`variations[${index}][price]`" placeholder="+ Harga" 
                                           class="w-full px-4 py-2 bg-white border-none rounded-xl text-sm font-bold text-sky-600 focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="col-span-1">
                                    <input type="number" x-model="variation.stock" :name="`variations[${index}][stock]`" placeholder="Stok" 
                                           class="w-full px-4 py-2 bg-white border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="col-span-1 flex items-center justify-center">
                                    <button type="button" @click="removeVariation(index)" class="text-slate-300 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="variations.length === 0">
                            <p class="text-center py-6 text-slate-400 text-sm italic">Belum ada variasi untuk produk ini.</p>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Kolom Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Organisasi & Gambar</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                            <select name="category_id" id="category_id" required 
                                    class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if(old('category_id', $product->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-bold text-slate-700 mb-2">Brand</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}"
                                   class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Gambar Produk</label>
                            <div class="group relative w-full aspect-square bg-slate-50 rounded-3xl overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                @else
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <input type="file" name="image" id="image" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <span class="text-white text-xs font-bold uppercase tracking-wider">Ganti Gambar</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div>
                                <p class="text-sm font-bold text-slate-700">Featured</p>
                                <p class="text-[10px] text-slate-400 font-medium">Tampil di beranda</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" @if(old('is_featured', $product->is_featured)) checked @endif class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <button type="submit" 
                            class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.products.index') }}" 
                       class="block w-full text-center py-4 bg-slate-50 text-slate-400 font-bold text-sm rounded-2xl hover:text-slate-600 transition-all">
                        Batalkan
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function productForm(initialVariations = []) {
        return {
            // Kita pastikan mapping harga tetap sesuai database Anda
            variations: initialVariations.map(v => ({
                id: v.id,
                variation_name: v.variation_name,
                sku: v.sku,
                additional_price: v.additional_price,
                stock: v.stock
            })),
            addVariation() {
                this.variations.push({ variation_name: '', sku: '', additional_price: 0, stock: 0 });
            },
            removeVariation(index) {
                this.variations.splice(index, 1);
            }
        }
    }
</script>
@endpush
@endsection