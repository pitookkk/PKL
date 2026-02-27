@extends('layouts.app')

@section('title', "Order #{$order->id} Details - Pitocom Admin")

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        }
     }" x-init="initObserver()">
    
    {{-- Header & Back Hub --}}
    <div class="reveal-section mb-12 text-left">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-indigo-500 transition-colors mb-6 group">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to Logistics Hub
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="text-left">
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase">Order <span class="text-indigo-600">#{{ $order->id }}.</span></h1>
                <p class="text-slate-500 mt-2 font-medium text-lg italic pl-1">Data record transaksi pelanggan pada sistem Pitocom.</p>
            </div>
            
            @php
                $statusStyles = [
                    'pending' => 'bg-amber-50 text-amber-500 border-amber-100',
                    'completed' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
                    'cancelled' => 'bg-rose-50 text-rose-500 border-rose-100',
                ];
                $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-400 border-slate-100';
            @endphp
            <div class="px-8 py-4 rounded-3xl border {{ $currentStyle }} shadow-sm text-center">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60">System Status</p>
                <p class="text-sm font-black uppercase tracking-widest mt-1">{{ $order->status }}</p>
            </div>
        </div>
        <div class="h-1.5 w-24 bg-indigo-500 mt-8 rounded-full text-left"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- Left Column: Order Items --}}
        <div class="lg:col-span-2 space-y-10 text-left">
            <div class="reveal-section bg-white p-10 lg:p-12 rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 bg-indigo-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.4em] mb-12 flex items-center relative">
                    <span class="bg-slate-900 text-white p-2.5 rounded-xl mr-4 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </span>
                    Invoiced Assets List
                </h2>
                
                <div class="divide-y divide-slate-50 relative">
                    @foreach($order->items as $index => $item)
                    <div class="py-8 flex flex-col md:flex-row items-center justify-between group first:pt-0 reveal-section" style="--item-delay: {{ $index * 100 }}ms; transition-delay: var(--item-delay);">
                        <div class="flex items-center space-x-8 text-left">
                            <div class="w-24 h-24 bg-slate-50 rounded-3xl overflow-hidden border border-slate-100 shadow-inner group-hover:rotate-3 transition-all duration-500 shrink-0">
                                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/150' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="text-left text-left">
                                <p class="font-black text-slate-900 text-xl tracking-tight leading-tight mb-2">{{ $item->product->name }}</p>
                                @if($item->variation)
                                    <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-500 text-[10px] font-black uppercase rounded-lg border border-indigo-100 shadow-sm text-left">
                                        {{ $item->variation->variation_name }}
                                    </span>
                                @endif
                                <div class="mt-3 flex items-center space-x-4 text-left">
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest text-left">Unit Qty: <span class="text-slate-900 ml-1">{{ $item->quantity }}</span></p>
                                    <div class="w-1 h-1 bg-slate-200 rounded-full"></div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest text-left">MSRP: <span class="text-slate-900 ml-1">Rp{{ number_format($item->price_at_purchase, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-6 md:mt-0">
                            <p class="text-2xl font-black text-indigo-600 tracking-tighter">Rp{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</p>
                            <p class="text-[9px] text-slate-300 font-black uppercase tracking-[0.2em] mt-1.5">Item Purchase Total</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Column: Side Hub --}}
        <div class="space-y-10 text-left">
            {{-- Customer Details Hub --}}
            <div class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100" style="transition-delay: 200ms">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-8 text-left">Client Identity</h3>
                <div class="space-y-6">
                    <div class="flex items-center p-5 bg-slate-50 rounded-3xl border border-slate-100 shadow-inner group hover:bg-white transition-all text-left">
                        <div class="w-14 h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-xl shadow-indigo-100 mr-5 shrink-0 group-hover:scale-110 transition-transform">
                            {{ substr($order->user->name, 0, 1) }}
                        </div>
                        <div class="truncate text-left">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 text-left">Account Name</p>
                            <p class="font-black text-slate-800 text-base truncate text-left">{{ $order->user->name }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center p-5 bg-slate-50 rounded-3xl border border-slate-100 shadow-inner group hover:bg-white transition-all text-left">
                        <div class="w-14 h-14 bg-white text-slate-400 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm mr-5 shrink-0 text-left">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="truncate text-left">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 text-left">Direct Mail</p>
                            <p class="font-black text-slate-800 text-sm truncate text-left">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary Hub --}}
            <div class="reveal-section bg-slate-900 p-10 rounded-[3rem] shadow-2xl text-white relative overflow-hidden text-left" style="transition-delay: 350ms">
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl text-left"></div>
                
                <h3 class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.4em] mb-10 relative">Order Summary</h3>
                
                <div class="space-y-4 pb-8 border-b border-white/5 relative text-left">
                    <div class="flex justify-between items-center text-left">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest text-left">Timestamp</span>
                        <span class="font-black text-white tracking-tight">{{ $order->order_date->format('d M, Y') }}</span>
                    </div>
                </div>
                
                <div class="py-10 relative text-left">
                    <p class="text-indigo-400 text-[10px] font-black uppercase tracking-[0.3em] mb-3 text-left">Grand Logistics Total</p>
                    <p class="text-4xl font-black tracking-tighter text-left">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>

                {{-- Update Status Hub --}}
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="relative mt-2 pt-8 border-t border-white/5 text-left">
                    @csrf
                    <label for="status" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 ml-1 text-left">Update Shipping Logic</label>
                    <div class="space-y-4 text-left">
                        <div class="relative text-left">
                            <select name="status" id="status" 
                                    class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500 text-white font-black text-xs uppercase tracking-widest appearance-none cursor-pointer text-left">
                                <option value="pending" class="bg-slate-900" @if($order->status == 'pending') selected @endif>Pending</option>
                                <option value="completed" class="bg-slate-900" @if($order->status == 'completed') selected @endif>Completed</option>
                                <option value="cancelled" class="bg-slate-900" @if($order->status == 'cancelled') selected @endif>Cancelled</option>
                            </select>
                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-white/30 text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <button type="submit" 
                                class="w-full bg-indigo-600 hover:bg-white hover:text-slate-950 text-white font-black py-5 rounded-[1.5rem] transition-all shadow-xl shadow-indigo-500/20 active:scale-95 text-[10px] uppercase tracking-[0.2em] flex justify-center items-center">
                            Deploy Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection