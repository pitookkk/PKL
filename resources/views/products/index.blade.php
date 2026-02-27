@extends('layouts.app')

@section('title', 'All Products - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 lg:py-12">
    {{-- 1. Header Section --}}
    <div class="mb-8 lg:mb-12 text-left animate-fade-in-up">
        <h1 class="text-3xl lg:text-5xl font-black text-slate-900 tracking-tight">Our Products</h1>
        <div class="h-1.5 w-16 bg-sky-500 mt-2 rounded-full"></div>
    </div>

    {{-- 2. Category & Sort Hub --}}
    {{-- Menggunakan justify-between agar Kategori di kiri dan Sortir di kanan pada Desktop --}}
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 lg:gap-8 relative z-50 animate-fade-in-up" style="animation-delay: 100ms">
        
        {{-- Kategori Hub --}}
        <div class="w-full lg:w-auto">
            {{-- View Desktop: Tombol Sejajar --}}
            <div class="hidden lg:flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" 
                   class="px-8 py-3 rounded-2xl font-bold text-sm transition-all duration-300 {{ !request('category') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50 hover:text-sky-600' }}">
                    All Products
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                       class="px-8 py-3 rounded-2xl font-bold text-sm transition-all duration-300 {{ request('category') == $cat->slug ? 'bg-sky-500 text-white shadow-lg shadow-sky-100' : 'bg-white text-slate-500 border border-slate-100 hover:bg-slate-50 hover:text-sky-600' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            {{-- View Mobile: Dropdown Selector --}}
            <div class="lg:hidden relative" x-data="{ openCat: false }">
                <button @click="openCat = !openCat" 
                        class="w-full flex items-center justify-between bg-white border border-slate-100 p-4 rounded-2xl shadow-sm font-black text-slate-700 active:scale-[0.98] transition-all relative z-50">
                    <span class="flex items-center text-sm uppercase tracking-widest">
                        <svg class="w-5 h-5 mr-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        {{ request('category') ? $categories->where('slug', request('category'))->first()->name : 'Select Category' }}
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="openCat ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="openCat" @click.away="openCat = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="absolute left-0 right-0 top-full mt-2 z-[100] bg-white border border-slate-100 rounded-3xl shadow-2xl p-2 max-h-[350px] overflow-y-auto">
                    <a href="{{ route('products.index') }}" class="block px-5 py-4 rounded-2xl text-xs font-black uppercase tracking-widest {{ !request('category') ? 'bg-sky-50 text-sky-600' : 'text-slate-600' }}">All Assets</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="block px-5 py-4 rounded-2xl text-xs font-black uppercase tracking-widest {{ request('category') == $cat->slug ? 'bg-sky-50 text-sky-600' : 'text-slate-600' }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sortir Hub --}}
        <div class="flex items-center w-full lg:w-auto">
            <div class="w-full lg:w-auto bg-white border border-slate-100 p-2 px-4 rounded-2xl shadow-sm flex items-center min-w-fit">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mr-3 border-r border-slate-200 pr-3 whitespace-nowrap">Sort By</label>
                <form action="{{ route('products.index') }}" method="GET" id="sortForm" class="flex-1 lg:flex-none">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    <select name="sort" onchange="document.getElementById('sortForm').submit()" 
                            class="bg-transparent border-none py-1 pl-0 pr-8 font-bold text-xs text-slate-700 focus:ring-0 cursor-pointer outline-none appearance-none uppercase tracking-tighter w-full lg:w-auto">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Arrival</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- 3. Product Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 relative z-0">
        @forelse ($products as $index => $product)
            <div class="group bg-white rounded-[1.5rem] lg:rounded-[2.5rem] p-3 lg:p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-50 flex flex-col animate-fade-in-up" 
                 style="animation-delay: {{ ($index % 4) * 100 }}ms;">
                
                <a href="{{ route('products.show', $product->slug) }}" class="flex-1">
                    <div class="relative overflow-hidden rounded-[1.2rem] lg:rounded-[1.8rem] mb-3 lg:mb-4 aspect-square bg-slate-50 shadow-inner">
                        <img loading="lazy" src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                    
                    <div class="px-1 text-left">
                        <p class="text-sky-500 text-[8px] lg:text-[10px] font-black uppercase tracking-widest mb-0.5">{{ $product->brand ?? 'Pitocom' }}</p>
                        <h3 class="font-bold text-slate-800 text-xs lg:text-lg group-hover:text-sky-600 transition-colors line-clamp-2 leading-tight h-8 lg:h-12 mb-2">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="flex flex-col gap-1">
                            @if ($product->is_flash_sale_active)
                                <p class="text-[9px] text-slate-400 line-through">Rp{{ number_format($product->base_price, 0, ',', '.') }}</p>
                                <p class="font-black text-rose-500 text-sm lg:text-xl tracking-tight leading-none">Rp{{ number_format($product->current_price, 0, ',', '.') }}</p>
                            @else
                                <p class="font-black text-slate-900 text-sm lg:text-xl tracking-tight leading-none">
                                    Rp{{ number_format($product->base_price, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">No Products Found</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-12 flex justify-center text-left">
        {{ $products->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>
</div>
@endsection