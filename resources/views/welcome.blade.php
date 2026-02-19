@extends('layouts.app')

@section('title', 'Pitocom - High Performance PC Parts & Builds')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
    100% { transform: translateX(300%); }
}
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    /* Animasi tambahan untuk feedback klik */
    .btn-click-effect {
        transition: transform 0.1s ease;
    }
    .btn-click-effect:active {
        transform: scale(0.9);
    }
</style>

<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- 1. Modern Hero Section --}}
    <section class="relative bg-slate-900 rounded-[2.5rem] overflow-hidden mb-16 shadow-2xl group animate-fade-in-up">
        <div class="absolute top-0 -right-20 w-80 h-80 bg-sky-500/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 -left-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row items-center">
            <div class="w-full lg:w-1/2 h-72 lg:h-[550px] overflow-hidden">
                <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=2070&auto=format&fit=crop" 
                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="High-end PC">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/40 via-transparent to-transparent"></div>
            </div>
            
            <div class="w-full lg:w-1/2 p-8 lg:p-16 text-left">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-sky-500/10 text-sky-400 text-sm font-bold mb-6 border border-sky-500/20">
                    🚀 New Tech Arrival 2026
                </span>
                <h1 class="text-4xl lg:text-7xl font-extrabold text-white leading-tight">
                    Build Your <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">Dream PC</span>
                </h1>
                <p class="mt-6 text-slate-400 text-lg lg:text-xl max-w-xl leading-relaxed">
                    Temukan komponen kelas atas dan sistem pre-built untuk gaming, kreasi konten, dan performa maksimal yang belum pernah Anda rasakan.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('products.index') }}" class="relative inline-flex items-center justify-center bg-sky-500 hover:bg-sky-400 text-white px-10 py-4 rounded-2xl font-bold transition-all duration-300 hover:shadow-[0_0_30px_rgba(14,165,233,0.5)] active:scale-95 group overflow-hidden">
                    <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-[-25deg] -translate-x-full group-hover:animate-[shimmer_1s_infinite]"></div>
                        <span class="relative">Shop Now</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                </a>
                    <button class="px-10 py-4 rounded-2xl border border-slate-700 text-white font-bold hover:bg-slate-800 transition-colors">
                        Catalog
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Featured Products Section --}}
    <section class="mb-20">
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Featured Products</h2>
                <div class="h-1 w-20 bg-sky-500 mt-2 rounded-full"></div>
            </div>
            <a href="{{ route('products.index') }}" class="text-sky-600 font-bold hover:underline">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($featuredProducts as $product)
                <div class="group bg-white rounded-[2rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 relative">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="relative overflow-hidden rounded-[1.5rem] mb-4 aspect-square">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            
                            <div class="absolute top-3 left-3">
                                <span class="glass-effect px-3 py-1 rounded-full text-[10px] font-bold text-slate-700 uppercase tracking-widest shadow-sm">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            {{-- Brand Dinamis dari Database --}}
                            <p class="text-sky-500 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $product->brand ?? 'Pitocom' }}</p>
                            
                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-sky-600 transition-colors truncate">
                                {{ $product->name }}
                            </h3>
                            
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <p class="text-slate-400 text-xs line-through">Rp {{ number_format($product->base_price * 1.15, 0, ',', '.') }}</p>
                                    <p class="font-black text-slate-900 text-xl tracking-tight">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                {{-- Tombol dengan Efek Klik --}}
                                <button class="btn-click-effect bg-slate-900 text-white p-3 rounded-xl hover:bg-sky-500 transition-all shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="text-6xl mb-4">📦</div>
                    <p class="text-slate-500 text-lg font-medium">No featured products available at the moment.</p>
                </div>
            @endforelse
        </div>
    </section>
    
    {{-- 3. Modern Category Showcase --}}
    <section>
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-900">Shop by Category</h2>
            <p class="text-slate-500 mt-2">Pilih kategori komponen yang sesuai dengan kebutuhanmu</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @forelse ($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="group relative flex flex-col items-center p-8 bg-white rounded-[2rem] border-2 border-slate-50 hover:border-sky-500/30 hover:shadow-xl transition-all duration-300 overflow-hidden text-center">
                    
                    <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-sky-500/5 rounded-full transition-all group-hover:scale-[3]"></div>
                    
                    {{-- Ikon Kategori Dinamis --}}
                    <div class="relative w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-sky-500 group-hover:text-white transition-all duration-300 shadow-sm">
                        @php
                            $catName = strtolower($category->name);
                            $icon = '🖥️';
                            if(str_contains($catName, 'laptop')) $icon = '💻';
                            elseif(str_contains($catName, 'gaming')) $icon = '🎮';
                            elseif(str_contains($catName, 'gpu') || str_contains($catName, 'graphic')) $icon = '🎞️';
                            elseif(str_contains($catName, 'part') || str_contains($catName, 'component')) $icon = '⚙️';
                        @endphp
                        {{ $icon }}
                    </div>
                    
                    <h3 class="relative mt-4 font-bold text-slate-700 group-hover:text-sky-600 transition-colors">{{ $category->name }}</h3>
                    <p class="relative text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest">
                        {{ $category->products_count ?? 0 }} Items &rarr;
                    </p>
                </a>
            @empty
                <p class="col-span-full text-center text-slate-500">No categories available.</p>
            @endforelse
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Contoh sederhana Toast/Feedback saat klik Add to Cart
    document.querySelectorAll('.btn-click-effect').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productName = this.closest('.group').querySelector('h3').innerText;
            // Di sini Anda bisa menambahkan logic AJAX untuk keranjang
            alert(productName + ' ditambahkan ke simulasi keranjang!');
        });
    });
</script>
@endpush

@endsection