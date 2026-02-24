@extends('layouts.app')

@section('title', $product->name)

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<div class="max-w-7xl mx-auto px-4 py-12" x-data="productPageData({{ json_encode($product) }})" x-init="init()">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 animate-fade-in-up">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 text-left">
            {{-- 1. Gallery Produk --}}
            <div>
                <div class="aspect-square overflow-hidden rounded-[2.5rem] bg-slate-50 border border-slate-100 shadow-inner">
                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/600' }}" 
                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                </div>
            </div>

            {{-- 2. Info Produk --}}
            <div>
                <p class="text-xs font-black text-sky-500 uppercase tracking-widest mb-2">{{ $product->category->name }}</p>
                <h1 class="text-4xl font-black text-slate-900 leading-tight mb-4 tracking-tight">{{ $product->name }}</h1>
                
                {{-- Rating Summary --}}
                <div class="flex items-center space-x-3 mb-6">
                    <div class="flex text-amber-400">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 fill-current {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z" /></svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-slate-400">({{ number_format($product->average_rating, 1) }} dari {{ $product->total_reviews }} Reviews)</span>
                </div>

                {{-- Box Harga --}}
                <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 mb-8 relative overflow-hidden">
                    <template x-if="product.is_flash_sale_active">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Normal</p>
                                <p class="text-2xl text-slate-300 line-through font-bold" x-text="formatPrice(parseFloat(product.base_price) + variationPrice)"></p>
                                <div class="flex items-baseline mt-1">
                                    <p class="text-5xl font-black text-rose-600 tracking-tighter" x-text="formatPrice(currentPrice)"></p>
                                    <span class="ml-3 bg-rose-500 text-white text-[10px] font-black px-2 py-1 rounded-lg uppercase animate-pulse tracking-tighter">Flash Sale</span>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-rose-100 text-center min-w-[120px]">
                                <p class="text-[10px] font-black text-rose-500 mb-1 uppercase tracking-widest">Ends In:</p>
                                <div class="text-2xl font-mono font-black text-slate-900" x-text="timer"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!product.is_flash_sale_active">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Terbaik</p>
                            <p class="text-5xl font-black text-slate-900 tracking-tighter" x-text="formatPrice(currentPrice)"></p>
                        </div>
                    </template>
                </div>

                {{-- Pilihan Variasi --}}
                @if($product->variations->isNotEmpty())
                <div class="mb-10">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Spesifikasi Komponen</label>
                    <select x-model="selectedVariationId" @change="updatePriceAndStock()" 
                            class="w-full px-6 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-sky-500 focus:ring-0 font-bold text-slate-700 shadow-sm cursor-pointer">
                        <option value="">-- Pilih Variasi --</option>
                        @foreach($product->variations as $v)
                            <option value="{{ $v->id }}">{{ $v->variation_name }} (+Rp {{ number_format($v->additional_price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="flex items-center mb-8 ml-1">
                    <div class="w-2.5 h-2.5 rounded-full mr-3 shadow-lg" :class="currentStock > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></div>
                    <p class="text-sm font-bold text-slate-600">Stock: <span x-text="currentStock" :class="currentStock > 0 ? 'text-emerald-600' : 'text-rose-600'"></span> ready</p>
                </div>
                
                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Add to Cart Form --}}
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <input type="hidden" name="variation_id" x-model="selectedVariationId">
                        <input type="hidden" name="quantity" value="1"> 
                        <button type="submit" 
                                class="w-full text-white font-black py-5 px-8 rounded-2xl transition-all shadow-xl flex items-center justify-center space-x-3 active:scale-[0.98]" 
                                :class="canAddToCart() ? 'bg-slate-900 hover:bg-sky-500 shadow-slate-200' : 'bg-slate-200 text-slate-400 cursor-not-allowed'" 
                                :disabled="!canAddToCart()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <span class="text-lg">Add to Cart</span>
                        </button>
                    </form>

                    {{-- Wishlist Button --}}
                    @auth
                        @if($isInWishlist)
                            <form action="{{ route('wishlist.remove', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-100 text-red-600 font-bold py-5 px-8 rounded-2xl flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                                    <span>Remove from Wishlist</span>
                                </button>
                            </form>
                        @else
                             <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-slate-100 text-slate-700 font-bold py-5 px-8 rounded-2xl flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    <span>Add to Wishlist</span>
                                </button>
                            </form>
                        @endif
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="w-full bg-slate-100 text-slate-700 font-bold py-5 px-8 rounded-2xl flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span>Add to Wishlist</span>
                        </a>
                    @endguest
                </div>

                {{-- WhatsApp Button --}}
                <a href="https://wa.me/6282281439842?text=Halo Admin Pitocom, saya tanya stok *{{ $product->name }}*. Ready gan?" 
                   target="_blank" class="mt-4 w-full flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white font-black py-5 px-8 rounded-2xl shadow-lg transition-all active:scale-[0.98]">
                    <span class="text-lg uppercase tracking-widest">Chat Admin WhatsApp</span>
                </a>
            </div>
        </div>

        {{-- 3. Review Section --}}
        <div class="mt-24 border-t border-slate-100 pt-16 text-left">
            <h3 class="text-3xl font-black text-slate-900 mb-10 tracking-tight uppercase">Customer Reviews ({{ $product->total_reviews }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse ($reviews as $review)
                    <div class="p-8 bg-slate-50/50 rounded-[2rem] border border-slate-100 transition-all hover:bg-white hover:shadow-md">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center font-black text-white shadow-lg">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-900 leading-tight">{{ $review->user->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex text-amber-400 mb-4">
                            @for ($i = 0; $i < $review->rating; $i++) 
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z" /></svg> 
                            @endfor
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed italic">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <p class="text-slate-400 font-medium italic col-span-2">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
            <div class="mt-12">{{ $reviews->links() }}</div>
        </div>
    </div>
</div>

<script>
function productPageData(initialProduct) {
    return {
        product: initialProduct,
        currentPrice: 0,
        currentStock: 0,
        variationPrice: 0,
        selectedVariationId: '',
        timer: '00:00:00',

        init() {
            this.updatePriceAndStock();
            if (this.product.is_flash_sale_active && this.product.flash_sale_end) {
                const endTime = new Date(this.product.flash_sale_end).getTime();
                const timerInterval = setInterval(() => {
                    const now = new Date().getTime();
                    const diff = endTime - now;
                    if (diff < 0) {
                        this.timer = "EXPIRED";
                        clearInterval(timerInterval);
                        return;
                    }
                    const h = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    const m = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    const s = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
                    this.timer = `${h}:${m}:${s}`;
                }, 1000);
            }
        },

        updatePriceAndStock() {
            let base = this.product.is_flash_sale_active ? parseFloat(this.product.flash_sale_price) : parseFloat(this.product.base_price);
            let stock = parseInt(this.product.stock);
            let vPrice = 0;

            if (this.selectedVariationId && this.product.variations.length > 0) {
                const variation = this.product.variations.find(v => v.id == this.selectedVariationId);
                if (variation) {
                    vPrice = parseFloat(variation.additional_price);
                    stock = parseInt(variation.stock);
                }
            }
            this.currentPrice = base + vPrice;
            this.variationPrice = vPrice; // Update variationPrice for the strikethrough display
            this.currentStock = stock;
        },

        canAddToCart() {
            const hasVariation = this.product.variations.length > 0;
            if (hasVariation) return this.selectedVariationId !== '' && this.currentStock > 0;
            return this.currentStock > 0;
        },

        formatPrice(n) {
            if (isNaN(n)) return '';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
        }
    }
}
</script>
@endsection
