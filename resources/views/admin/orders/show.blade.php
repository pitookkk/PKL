@extends('layouts.app')

@section('title', "Order #{$order->id} Details - Pitocom Admin")

@section('content')
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-4 py-10">
        {{-- Header & Back Button --}}
        <div class="mb-10 animate-fade-in-up text-left">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-bold text-sky-500 hover:text-sky-600 transition mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to All Orders
            </a>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Order #{{ $order->id }}</h1>
                
                @php
                    $statusStyles = [
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                    ];
                    $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                @endphp
                <span class="inline-block px-6 py-2 rounded-full border {{ $currentStyle }} text-xs font-black uppercase tracking-[0.2em]">
                    Status: {{ $order->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Order Items List --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Order Items
                    </h2>
                    
                    <div class="divide-y divide-slate-50">
                        @foreach($order->items as $item)
                        <div class="py-6 flex flex-col md:flex-row items-center justify-between group first:pt-0">
                            <div class="flex items-center space-x-6">
                                <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden shadow-sm">
                                    <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/150' }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="text-left">
                                    <p class="font-black text-slate-900 text-lg leading-tight">{{ $item->product->name }}</p>
                                    @if($item->variation)
                                        <span class="inline-block mt-1 px-3 py-1 bg-sky-50 text-sky-500 text-[10px] font-bold uppercase rounded-lg border border-sky-100">
                                            {{ $item->variation->variation_name }}
                                        </span>
                                    @endif
                                    <p class="text-sm text-slate-400 mt-2 font-medium italic">Quantity: {{ $item->quantity }} units</p>
                                </div>
                            </div>
                            <div class="text-right mt-4 md:mt-0">
                                <p class="text-xl font-black text-slate-900">Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }} / unit</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar: Customer & Summary --}}
            <div class="space-y-8 text-left">
                {{-- Customer Details --}}
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Customer Details</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-slate-50 rounded-2xl">
                            <div class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center font-bold mr-4">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-none">Name</p>
                                <p class="font-bold text-slate-800">{{ $order->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-slate-50 rounded-2xl">
                            <div class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-none">Email</p>
                                <p class="font-bold text-slate-800 lowercase">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Summary & Update --}}
                <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-xl font-black mb-6">Order Summary</h3>
                    <div class="space-y-3 pb-6 border-b border-slate-800">
                        <div class="flex justify-between text-slate-400 text-sm">
                            <span>Order Date</span>
                            <span class="font-bold text-white">{{ $order->order_date->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="py-6">
                        <p class="text-sky-400 text-xs font-bold uppercase tracking-widest">Total Bill</p>
                        <p class="text-4xl font-black mt-1">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>

                    {{-- Update Status Form --}}
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-4 pt-6 border-t border-slate-800">
                        @csrf
                        <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 text-left">Update Delivery Status</label>
                        <div class="flex flex-col space-y-3">
                            <select name="status" id="status" 
                                    class="w-full px-5 py-3 bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-sky-500 text-white font-bold text-sm transition-all appearance-none cursor-pointer">
                                <option value="pending" @if($order->status == 'pending') selected @endif>Pending</option>
                                <option value="completed" @if($order->status == 'completed') selected @endif>Completed</option>
                                <option value="cancelled" @if($order->status == 'cancelled') selected @endif>Cancelled</option>
                            </select>
                            <button type="submit" 
                                    class="w-full bg-sky-500 hover:bg-sky-400 text-white font-black py-4 rounded-xl transition-all shadow-lg shadow-sky-500/20 active:scale-95">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection