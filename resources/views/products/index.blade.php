@extends('layouts.app')

@section('title', 'All Products - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- 1. Header Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight text-left">Our Products</h1>
        <div class="h-1.5 w-20 bg-sky-500 mt-2 rounded-full"></div>
    </div>

    {{-- 2. Filter & Sort Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        {{-- Navigasi Kategori (Kiri) --}}
        <div class="flex flex-wrap gap-3 text-left">
            {{-- Tombol All Products --}}
            <a href="{{ route('products.index') }}" 
               class="px-8 py-3 rounded-2xl font-bold text-sm transition-all duration-300 {{ !request('category') ? 'bg-sky-500 text-white shadow-lg shadow-sky-100' : 'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50' }}">
                All Products
            </a>

            {{-- Looping Kategori dari Database --}}
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                   class="px-8 py-3 rounded-2xl font-bold text-sm transition-all duration-300 {{ request('category') == $cat->slug ? 'bg-sky-500 text-white shadow-lg shadow-sky-100' : 'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Fitur Sortir (Kanan) --}}
        <div class="flex items-center space-x-3">
            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Sort By:</label>
            <form action="{{ route('products.index') }}" method="GET" id="sortForm">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <select name="sort" onchange="document.getElementById('sortForm').submit()" 
                        class="bg-white border border-slate-100 px-4 py-2 rounded-xl font-bold text-sm text-slate-700 focus:ring-2 focus:ring-sky-500 outline-none cursor-pointer shadow-sm">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                </select>
            </form>
        </div>
    </div>

    {{-- 3. Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse ($products as $product)
            <div class="group bg-white rounded-[2.5rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 relative text-left">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="relative overflow-hidden rounded-[1.8rem] mb-4 aspect-square">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <div class="absolute top-3 left-3">
                            <span class="bg-white/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-slate-700 uppercase tracking-widest shadow-sm border border-white/20">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="px-2">
                        <p class="text-sky-500 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $product->brand ?? 'Pitocom' }}</p>
                        <h3 class="font-bold text-slate-800 text-lg group-hover:text-sky-600 transition-colors truncate">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                @if ($product->is_flash_sale_active)
                                    <p class="text-slate-400 text-xs line-through">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                    <p class="font-black text-rose-500 text-xl tracking-tight">Rp {{ number_format($product->current_price, 0, ',', '.') }}</p>
                                @else
                                    <p class="font-black text-slate-900 text-xl tracking-tight">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                            <div class="bg-slate-900 text-white p-2.5 rounded-xl group-hover:bg-sky-500 transition-colors shadow-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-24 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="text-6xl mb-4 italic">📦</div>
                <p class="text-xl text-slate-500 font-bold uppercase tracking-widest">Produk tidak ditemukan</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-sky-600 font-bold hover:underline">
                    &larr; Reset Pencarian
                </a>
            </div>
        @endforelse
    </div>

    {{-- 4. Pagination --}}
    <div class="mt-16 flex justify-center">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection