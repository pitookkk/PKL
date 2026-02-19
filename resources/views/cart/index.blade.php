@extends('layouts.app')

@section('title', 'Shopping Cart - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    {{-- Header Keranjang --}}
    <div class="flex items-center space-x-4 mb-10">
        <div class="bg-sky-500 p-3 rounded-2xl shadow-lg shadow-sky-200 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </div>
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Your Cart</h1>
            <p class="text-slate-500 font-medium">Review barang belanjaanmu sebelum checkout.</p>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-2xl mb-8 flex items-center animate-fade-in-up">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(!empty($cart))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- List Item Keranjang --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @foreach($cart as $id => $details)
                    <div class="p-6 md:p-8 flex flex-col md:flex-row items-center justify-between group transition-colors hover:bg-slate-50/50">
                        <div class="flex items-center space-x-6 w-full">
                            {{-- Image Preview --}}
                            <div class="relative w-24 h-24 md:w-32 md:h-32 flex-shrink-0">
                                <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/150' }}" 
                                     alt="{{ $details['name'] }}" 
                                     class="w-full h-full object-cover rounded-3xl shadow-sm group-hover:scale-105 transition-transform duration-500">
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-sky-600 transition-colors">{{ $details['name'] }}</h3>
                                        @if($details['variation_name'])
                                            <span class="inline-block mt-1 px-3 py-1 bg-sky-50 text-sky-600 text-[10px] font-bold uppercase tracking-wider rounded-full border border-sky-100">
                                                {{ $details['variation_name'] }}
                                            </span>
                                        @endif
                                    </div>
                                    {{-- Hapus Item --}}
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-red-500 transition-colors p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-lg font-bold text-slate-900">
                                        Rp {{ number_format($details['price'], 0, ',', '.') }}
                                    </div>
                                    
                                    {{-- Update Quantity --}}
                                    <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center bg-slate-100 rounded-2xl p-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" 
                                               class="w-12 bg-transparent border-none text-center font-bold focus:ring-0 text-slate-700 p-0">
                                        <button type="submit" class="bg-white text-sky-500 p-2 rounded-xl shadow-sm hover:text-sky-600 transition-all active:scale-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-sky-500 font-bold hover:text-sky-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Tambah Produk Lain
            </a>
        </div>
        
        {{-- Ringkasan Pesanan --}}
        <div class="space-y-6">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden sticky top-28">
                {{-- Dekorasi Glow --}}
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-sky-500/20 rounded-full blur-[80px]"></div>

                <h3 class="text-2xl font-black mb-8 tracking-tight">Order Summary</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between text-slate-400 font-medium">
                        <span>Items Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400 font-medium">
                        <span>Estimated Tax</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="h-px bg-slate-800 my-4"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-slate-300">Total Bill</span>
                        <span class="text-3xl font-black text-sky-400 tracking-tighter">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-sky-500 hover:bg-sky-400 text-white font-black py-5 rounded-[1.5rem] transition-all duration-300 shadow-lg shadow-sky-500/20 active:scale-95 flex items-center justify-center space-x-2">
                        <span>Proceed to Checkout</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </form>

                <p class="text-center text-slate-500 text-xs mt-6">
                    🔒 Secure Payment powered by Pitocom Engine
                </p>
            </div>
        </div>
    </div>
    @else
    {{-- Empty State --}}
    <div class="text-center bg-white p-20 rounded-[3rem] shadow-sm border border-slate-100 max-w-2xl mx-auto">
        <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-6xl">
            🛒
        </div>
        <h2 class="text-3xl font-black text-slate-900 mb-2">Keranjangmu Kosong</h2>
        <p class="text-slate-500 mb-10">Sepertinya kamu belum memilih komponen impianmu hari ini.</p>
        <a href="{{ route('products.index') }}" class="bg-sky-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-sky-400 transition-all shadow-lg shadow-sky-100 inline-block">
            Start Building Now
        </a>
    </div>
    @endif
</div>
@endsection