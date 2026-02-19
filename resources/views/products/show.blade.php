@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 p-8 lg:p-12">
            
            {{-- Product Gallery --}}
            <div class="space-y-4">
                <div class="relative overflow-hidden rounded-3xl bg-slate-50 aspect-square group">
                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/600' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </div>
                {{-- Kamu bisa menambahkan gallery kecil di sini nanti --}}
            </div>

            {{-- Product Info & Actions --}}
            <div x-data="productVariationSelector({{ json_encode($product->variations) }}, {{ $product->base_price }}, {{ $product->stock ?? 0 }})" class="flex flex-col">
                <nav class="flex mb-4 text-sm font-medium text-slate-400">
                    <a href="/" class="hover:text-sky-500 transition">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-slate-600">{{ $product->category->name }}</span>
                </nav>

                <p class="text-sky-500 font-bold uppercase tracking-widest text-sm">{{ $product->brand ?? 'Pitocom Exclusive' }}</p>
                <h1 class="text-4xl lg:text-5xl font-black text-slate-900 mt-2 leading-tight">{{ $product->name }}</h1>
                
                <div class="mt-6 p-6 bg-slate-50 rounded-2xl">
                    <p class="text-slate-500 text-sm mb-1">Total Price</p>
                    <p class="text-4xl font-black text-sky-600" x-text="formatPrice(currentPrice)"></p>
                </div>

                {{-- Variation Selection --}}
                <div class="mt-8 space-y-6" x-show="variations.length > 0">
                    <div>
                        <label for="variation_select" class="block text-sm font-bold text-slate-700 mb-3">Pilih Spesifikasi / Opsi:</label>
                        <select id="variation_select" 
                                x-model="selectedVariation" 
                                @change="updatePrice" 
                                class="w-full p-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-sky-500 focus:ring-0 transition-all font-semibold text-slate-700">
                            <option value="">-- Pilih Opsi --</option>
                            <template x-for="variation in variations" :key="variation.id">
                                <option :value="variation.id" x-text="`${variation.variation_name} (+${formatPrice(variation.additional_price)})`"></option>
                            </template>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex items-center space-x-4">
                    <div class="flex items-center px-4 py-2 bg-slate-100 rounded-full">
                        <div class="w-2 h-2 rounded-full mr-2" :class="currentStock > 0 ? 'bg-green-500' : 'bg-red-500'"></div>
                        <p class="text-sm font-bold text-slate-600">Stock: <span x-text="currentStock"></span></p>
                    </div>
                </div>
                
                {{-- Add to Cart Action --}}
                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-10">
                    @csrf
                    <input type="hidden" name="variation_id" x-model="selectedVariation">
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center border-2 border-slate-100 rounded-2xl px-4 py-2">
                            <label class="text-xs font-bold text-slate-400 mr-4">QTY</label>
                            <input type="number" name="quantity" value="1" min="1" :max="currentStock" 
                                   class="w-12 text-center font-bold border-none focus:ring-0 p-0 text-slate-800">
                        </div>

                        <button type="submit"
                                class="flex-1 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 shadow-lg shadow-sky-200 active:scale-95"
                                :class="canAddToCart() ? 'bg-sky-500 hover:bg-sky-600' : 'bg-slate-300 cursor-not-allowed'"
                                :disabled="!canAddToCart()">
                            <span x-text="currentStock > 0 ? 'Add to Shopping Cart' : 'Out of Stock'"></span>
                        </button>
                    </div>
                </form>
                
                {{-- Description --}}
                <div class="mt-12 border-t border-slate-100 pt-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Product Description
                    </h3>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Alpine.js untuk Logika Variasi --}}
<script>
    function productVariationSelector(variations, basePrice, productStock) {
        return {
            variations: variations,
            selectedVariation: '',
            currentPrice: basePrice,
            currentStock: productStock,
            
            updatePrice() {
                if (this.selectedVariation === '') {
                    this.currentPrice = basePrice;
                    this.currentStock = productStock;
                } else {
                    const variation = this.variations.find(v => v.id == this.selectedVariation);
                    if (variation) {
                        // Harga = Base Price + Additional Price dari tabel variations
                        this.currentPrice = parseFloat(basePrice) + parseFloat(variation.additional_price);
                        // Stok diambil dari variasi jika ada, jika tidak pakai stok produk
                        this.currentStock = variation.stock;
                    }
                }
            },
            
            formatPrice(price) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(price);
            },
            
            canAddToCart() {
                // Bisa klik beli jika stok ada DAN (tidak ada variasi ATAU variasi sudah dipilih)
                const hasVariations = this.variations.length > 0;
                const variationSelected = this.selectedVariation !== '';
                
                if (hasVariations) {
                    return variationSelected && this.currentStock > 0;
                }
                return this.currentStock > 0;
            }
        }
    }
</script>
@endsection