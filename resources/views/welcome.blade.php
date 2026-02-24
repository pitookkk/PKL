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

    @keyframes scroll {

        from { transform: translateX(0); }

        to { transform: translateX(-50%); }

    }

    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }

    .animate-scroll { animation: scroll 30s linear infinite; }

   

    .glass-effect {

        background: rgba(255, 255, 255, 0.8);

        backdrop-filter: blur(10px);

    }



    .btn-click-effect {

        transition: transform 0.1s ease;

    }

    .btn-click-effect:active {

        transform: scale(0.9);

    }

    .fade-mask {

        mask-image: linear-gradient(to right, transparent 0, black 128px, black calc(100% - 128px), transparent 100%);

    }

</style>



<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- 1. Hero Section --}}

    <section class="relative bg-slate-900 rounded-[2.5rem] overflow-hidden mb-16 shadow-2xl group animate-fade-in-up text-left">

        <div class="absolute top-0 -right-20 w-80 h-80 bg-sky-500/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="absolute bottom-0 -left-20 w-80 h-80 bg-indigo-600/20 rounded-full blur-[100px] pointer-events-none"></div>



        <div class="relative flex flex-col lg:flex-row items-center">

            <div class="w-full lg:w-1/2 h-72 lg:h-[600px] overflow-hidden">

                <img src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=2070&auto=format&fit=crop"

                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="High-end PC">

                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/40 via-transparent to-transparent"></div>

            </div>

           

            <div class="w-full lg:w-1/2 p-8 lg:p-16">

                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-sky-500/10 text-sky-400 text-sm font-bold mb-6 border border-sky-500/20">

                    🚀 New Tech Arrival 2026

                </span>

                <h1 class="text-4xl lg:text-7xl font-extrabold text-white leading-tight">

                    Build Your <br/>

                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">Dream PC</span>

                </h1>

                <p class="mt-6 text-slate-400 text-lg lg:text-xl max-w-xl leading-relaxed">

                    Temukan komponen kelas atas dan sistem pre-built untuk gaming, kreasi konten, dan performa maksimal.

                </p>



                <div class="mt-8 grid grid-cols-2 gap-4 max-w-md">

                    <div class="flex items-center space-x-3 text-slate-300">

                        <div class="bg-white/10 p-2 rounded-lg text-sky-400">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>

                        </div>

                        <span class="text-sm font-medium">Extreme Performance</span>

                    </div>

                    <div class="flex items-center space-x-3 text-slate-300">

                        <div class="bg-white/10 p-2 rounded-lg text-sky-400">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>

                        </div>

                        <span class="text-sm font-medium">Certified Hardware</span>

                    </div>

                </div>



                <div class="mt-10">

                    <a href="{{ route('products.index') }}" class="relative inline-flex items-center justify-center bg-sky-500 hover:bg-sky-400 text-white px-10 py-4 rounded-2xl font-bold transition-all duration-300 hover:shadow-[0_0_30px_rgba(14,165,233,0.5)] active:scale-95 group/btn overflow-hidden">

                        <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-[-25deg] -translate-x-full group-hover/btn:animate-[shimmer_1s_infinite]"></div>

                        <span class="relative text-lg">Shop Now</span>

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />

                        </svg>

                    </a>

                </div>

            </div>

        </div>

    </section>



    <section class="py-12">

        <div class="text-center mb-8">

            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em]">TRUSTED BY TOP BRANDS</h3>

        </div>

        <div class="relative w-full overflow-hidden fade-mask">

            <div class="flex w-max">

                @php $brands = ['Nvidia', 'Intel', 'AMD', 'MSI', 'Gigabyte', 'Corsair', 'Asus', 'Lian Li']; @endphp

                <div class="flex w-max items-center animate-scroll">

                    @foreach ($brands as $brand)

                        <img src="{{ asset('images/brands/'.strtolower(str_replace(' ', '-', $brand)).'.svg') }}" alt="{{ $brand }}"

                             onerror="this.src='https://via.placeholder.com/150?text={{ $brand }}'"

                             class="h-8 mx-12 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-300">

                    @endforeach

                </div>

                <div class="flex w-max items-center animate-scroll">

                    @foreach ($brands as $brand)

                        <img src="{{ asset('images/brands/'.strtolower(str_replace(' ', '-', $brand)).'.svg') }}" alt="{{ $brand }}"

                             onerror="this.src='https://via.placeholder.com/150?text={{ $brand }}'"

                             class="h-8 mx-12 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all duration-300">

                    @endforeach

                </div>

            </div>

        </div>

    </section>



    {{-- Flash Sale Section --}}

    @if($flashSaleProducts->isNotEmpty())

    <section class="mb-20">

        <div class="flex items-end justify-between mb-10 text-left">

            <div>

                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center">

                    <span class="text-rose-500 mr-2 text-4xl">⚡</span> Flash Sale

                </h2>

                <p class="text-slate-500 mt-1 font-medium">Jangan sampai ketinggalan promo komponen hardware terbaik!</p>

            </div>

            <a href="{{ route('products.index', ['promo' => 'flash-sale']) }}" class="text-sky-600 font-bold hover:text-sky-700 transition-colors">View All &rarr;</a>

        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @foreach($flashSaleProducts as $product)

                <div class="group bg-white rounded-[2rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border-2 border-rose-500/10 relative text-left h-full flex flex-col"

                     x-data="countdown('{{ $product->flash_sale_end->toIso8601String() }}', {{ $product->stock }})" x-init="init()">

                   

                    <a href="{{ route('products.show', $product->slug) }}" class="flex-1">

                        <div class="relative overflow-hidden rounded-[1.5rem] mb-4 aspect-square">

                            <img loading="lazy" src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}"

                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                           

                            <div class="absolute top-3 left-3">

                                <span class="bg-rose-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">

                                    Limited Time

                                </span>

                            </div>

                        </div>



                        <div class="px-2">

                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-rose-500 transition-colors truncate">{{ $product->name }}</h3>

                           

                            <div class="mt-3 bg-slate-900 rounded-2xl p-3 flex items-center justify-between">

                                <div class="flex items-center space-x-1.5 w-full justify-around">

                                    <div class="text-center">

                                        <p class="text-xs font-black text-white" x-text="remaining.days"></p>

                                        <p class="text-[8px] text-slate-500 uppercase font-bold">Day</p>

                                    </div>

                                    <span class="text-slate-700 font-bold">:</span>

                                    <div class="text-center">

                                        <p class="text-xs font-black text-white" x-text="remaining.hours"></p>

                                        <p class="text-[8px] text-slate-500 uppercase font-bold">Hrs</p>

                                    </div>

                                    <span class="text-slate-700 font-bold">:</span>

                                    <div class="text-center">

                                        <p class="text-xs font-black text-white" x-text="remaining.minutes"></p>

                                        <p class="text-[8px] text-slate-500 uppercase font-bold">Min</p>

                                    </div>

                                    <span class="text-slate-700 font-bold">:</span>

                                    <div class="text-center">

                                        <p class="text-xs font-black text-rose-500" x-text="remaining.seconds"></p>

                                        <p class="text-[8px] text-slate-500 uppercase font-bold">Sec</p>

                                    </div>

                                </div>

                            </div>



                            <div class="mt-5 flex items-end justify-between">

                                <div>

                                    <p class="text-slate-400 text-[10px] font-bold line-through">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>

                                    <p class="font-black text-rose-500 text-xl tracking-tighter">

                                        Rp {{ number_format($product->flash_sale_price ?? $product->current_price, 0, ',', '.') }}

                                    </p>

                                </div>

                                <div class="btn-click-effect bg-rose-500 text-white p-3 rounded-2xl shadow-lg shadow-rose-200">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>

                                </div>

                            </div>



                            <div class="mt-4 mb-2">

                                <div class="flex justify-between items-center mb-1.5">

                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stock Left</p>

                                    <p class="text-[10px] font-black text-rose-500">{{ $product->stock }} Units</p>

                                </div>

                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">

                                    <div class="bg-rose-500 h-full rounded-full transition-all duration-1000" :style="`width: ${stockPercentage}%`"></div>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>

            @endforeach

        </div>

    </section>

    @endif



    {{-- 2. Special Bundles Section --}}

    @if($bundles->isNotEmpty())

    <section class="mb-20">

        <div class="flex items-end justify-between mb-10 text-left">

            <div>

                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Special <span class="text-indigo-600">Bundles</span></h2>

                <div class="h-1 w-20 bg-indigo-500 mt-2 rounded-full"></div>

            </div>

            <a href="{{ route('bundles.index') }}" class="text-indigo-600 font-bold hover:text-indigo-700">View All &rarr;</a>

        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            @foreach($bundles as $bundle)

                <div class="group bg-white rounded-[3rem] p-6 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col md:flex-row items-center gap-8 text-left">

                    <div class="w-full md:w-2/5 aspect-square rounded-[2.5rem] overflow-hidden bg-slate-50">

                        <img src="{{ $bundle->image_path ? asset('storage/' . $bundle->image_path) : 'https://via.placeholder.com/300' }}"

                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                    </div>

                    <div class="flex-1">

                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Save More</span>

                        <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $bundle->name }}</h3>

                        <p class="text-slate-500 text-sm mb-6 line-clamp-2">{{ $bundle->description }}</p>

                       

                        <div class="mb-6 space-y-2">

                            @foreach($bundle->products->take(3) as $p)

                                <div class="flex items-center text-xs font-bold text-slate-400">

                                    <svg class="w-3 h-3 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>

                                    {{ $p->name }}

                                </div>

                            @endforeach

                        </div>



                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Bundle Price</p>

                                <p class="text-2xl font-black text-indigo-600 tracking-tighter">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>

                            </div>

                            <a href="{{ route('bundles.index') }}" class="bg-slate-900 text-white p-4 rounded-2xl hover:bg-indigo-600 transition-all shadow-lg active:scale-90">

                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </section>

    @endif



    {{-- 2. Featured Products Section --}}

    <section class="mb-20">

        <div class="flex items-end justify-between mb-10 text-left">

            <div>

                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">Featured Products</h2>

                <div class="h-1 w-20 bg-sky-500 mt-2 rounded-full"></div>

            </div>

            <a href="{{ route('products.index') }}" class="text-sky-600 font-bold hover:text-sky-700">View All &rarr;</a>

        </div>



        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @forelse ($featuredProducts as $product)

                <div class="group bg-white rounded-[2rem] p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 relative text-left flex flex-col">

                    <a href="{{ route('products.show', $product->slug) }}" class="flex-1">

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

                            <p class="text-sky-500 text-[10px] font-bold uppercase tracking-widest mb-1">{{ $product->brand ?? 'Pitocom' }}</p>

                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-sky-600 transition-colors truncate">{{ $product->name }}</h3>



                            {{-- Rating Badge --}}

                            <div class="flex items-center space-x-1 mt-1">

                                <div class="flex text-yellow-400">

                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>

                                </div>

                                <span class="text-[10px] font-bold text-slate-400">

                                    {{ number_format($product->reviews_avg_rating ?? 5.0, 1) }} ({{ $product->reviews_count ?? 0 }})

                                </span>

                            </div>

                           

                            <div class="mt-4 flex items-center justify-between">

                                <div>

                                    <p class="text-slate-400 text-xs line-through">Rp {{ number_format($product->base_price * 1.15, 0, ',', '.') }}</p>

                                    <p class="font-black text-slate-900 text-xl tracking-tight">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>

                                </div>

                                <div class="btn-click-effect bg-slate-900 text-white p-3 rounded-2xl hover:bg-sky-500 transition-all shadow-lg">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>

            @empty

                <div class="col-span-full py-20 text-center">

                    <p class="text-slate-500 text-lg font-medium italic">No featured products available.</p>

                </div>

            @endforelse

        </div>

    </section>

   

    {{-- 3. Category Showcase --}}

    <section class="mb-20">

        <div class="text-center mb-12">

            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">SHOP BY CATEGORY</h2>

            <p class="text-slate-500 mt-2 font-medium">Pilih kategori komponen yang sesuai dengan kebutuhanmu</p>

        </div>



        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">

            @forelse ($categories as $category)

                <a href="{{ route('products.index', ['category' => $category->slug]) }}"

                   class="group relative flex flex-col items-center p-8 bg-white rounded-[2rem] border-2 border-slate-50 hover:border-sky-500/30 hover:shadow-xl transition-all duration-300 overflow-hidden text-center">

                    <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-sky-500/5 rounded-full transition-all group-hover:scale-[3]"></div>

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

                    <p class="relative text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest italic">{{ $category->products_count ?? 0 }} Items</p>

                </a>

            @empty

                <p class="col-span-full text-center text-slate-500">No categories available.</p>

            @endforelse

        </div>

    </section>



    <section class="mb-20" x-data="instagramGallery()">

        <div class="text-center mb-12">

            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">#PITOCOMBUILDS</h2>

            <p class="text-slate-500 mt-2">Lihat hasil rakitan PC impian pelanggan kami!</p>

        </div>



        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

            <template x-for="post in instagramPosts" :key="post.id">

                <div @click="showPost(post)" class="relative aspect-square overflow-hidden rounded-2xl cursor-pointer group shadow-sm">

                    <img loading="lazy" :src="post.image" alt="Pitocom Build" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                    <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">

                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>

                    </div>

                </div>

            </template>

        </div>



        <div class="text-center mt-12">

            <a href="https://www.instagram.com/pitookk__/" target="_blank" class="inline-flex items-center bg-slate-900 text-white font-bold py-4 px-8 rounded-2xl shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95">

                Follow @Pitocom &rarr;

            </a>

        </div>



        <div x-show="openModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" @keydown.escape.window="openModal = false" style="display: none;">

            <div class="bg-white rounded-[2.5rem] shadow-2xl p-8 w-full max-w-2xl relative overflow-hidden border border-slate-100" @click.away="openModal = false">

                <button @click="openModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-rose-500 transition-colors">

                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>

                </button>

                <div class="flex flex-col md:flex-row gap-8">

                    <div class="md:w-1/2 aspect-square rounded-[1.5rem] overflow-hidden shadow-lg">

                        <img :src="currentPost.image" alt="Build" class="w-full h-full object-cover">

                    </div>

                    <div class="md:w-1/2 flex flex-col justify-center text-left">

                        <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Pitocom Showcase</h3>

                        <p class="text-slate-600 leading-relaxed mb-6 italic" x-text="currentPost.testimonial"></p>

                        <div class="flex flex-wrap gap-2">

                            <template x-for="tag in currentPost.tags" :key="tag">

                                <span class="bg-sky-50 text-sky-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest border border-sky-100" x-text="tag"></span>

                            </template>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- Trust Badges Component --}}

    <section class="mt-16 mb-20 text-left">

        <x-trust-badges-section />

    </section>

</div>



<script>

    function countdown(endDate, initialStock) {

        return {

            remaining: { days: '00', hours: '00', minutes: '00', seconds: '00' },

            stockPercentage: 0,

            init() {

                // Calculate stock percentage

                this.stockPercentage = Math.min((initialStock / 20) * 100, 100);



                // Set up timer

                const target = new Date(endDate).getTime();

                setInterval(() => {

                    const now = new Date().getTime();

                    const distance = target - now;

                    if (distance < 0) {

                        this.remaining = { days: '00', hours: '00', minutes: '00', seconds: '00' };

                        return;

                    }

                    this.remaining.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');

                    this.remaining.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');

                    this.remaining.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');

                    this.remaining.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

                }, 1000);

            }

        }

    }



    function instagramGallery() {

        return {

            openModal: false,

            currentPost: {},

            instagramPosts: [

                { id: 1, image: 'https://images.unsplash.com/photo-1594905662978-03a1a2d7eb24?w=600&auto=format&fit=crop&q=60', testimonial: 'Rakitan PC dari Pitocom memang juara! Performa ngebut, desain rapi.', tags: ['#GamingPC', '#Ryzen5'] },

                { id: 2, image: 'https://images.unsplash.com/photo-1696710257827-75e2e5954059?w=600&auto=format&fit=crop&q=60', testimonial: 'Service Pitocom sangat responsif. PC saya kembali prima!', tags: ['#CustomPC', '#RGBbuild'] },

                { id: 3, image: 'https://images.unsplash.com/photo-1636609168915-d561a1d30e87?w=600&auto=format&fit=crop&q=60', testimonial: 'Mantap! Sesuai ekspektasi dan pengiriman aman.', tags: ['#WorkstationPC', '#IntelCore'] },

                { id: 4, image: 'https://images.unsplash.com/photo-1603481588273-2f908a9a7a1b?w=600&auto=format&fit=crop&q=60', testimonial: 'Komponen lengkap, pilihan terbaik ada di Pitocom.', tags: ['#PCbuild', '#NvidiaGeForce'] },

                { id: 5, image: 'https://images.unsplash.com/photo-1648539356777-6a6d9ba6bcd0?w=600&auto=format&fit=crop&q=60', testimonial: 'PC Gaming saya sekarang bisa melibas semua game berat!', tags: ['#GamingSetup', '#HighPerformance'] }

            ],

            showPost(post) {

                this.currentPost = post;

                this.openModal = true;

            }

        }

    }

</script>

@endsection