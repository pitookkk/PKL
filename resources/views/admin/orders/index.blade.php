@extends('layouts.app')

@section('title', 'Manage Orders - Pitocom Admin')

@section('content')
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-6 py-12" x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 text-left fade-in-up">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Customer <span class="text-indigo-500">Orders</span></h1>
                <p class="text-slate-500 font-medium mt-1">Pantau dan kelola seluruh transaksi masuk secara real-time.</p>
            </div>
            <div class="flex space-x-4">
                <button class="px-8 py-4 glass-card text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 transition-all active:scale-95 shadow-xl">
                    Export Logistics
                </button>
            </div>
        </div>

        {{-- Bento Grid Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12 fade-in-up" style="animation-delay: 0.1s">
            <div class="glass-card p-8 rounded-[2.5rem] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Pending</p>
                <h3 class="text-4xl font-black text-amber-500 mt-2">{{ $orders->where('status', 'pending')->count() }}</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] border border-white/5 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Completed</p>
                <h3 class="text-4xl font-black text-emerald-500 mt-2">{{ $orders->where('status', 'completed')->count() }}</h3>
            </div>
            <div class="bg-indigo-600 p-8 rounded-[2.5rem] shadow-2xl shadow-indigo-500/20 text-white md:col-span-2 relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                <p class="text-indigo-200 text-[10px] font-black uppercase tracking-widest">Total Revenue</p>
                <h3 class="text-3xl font-black mt-2 tracking-tighter">Rp {{ number_format($orders->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h3>
                <p class="text-indigo-300 text-[10px] mt-2 font-bold opacity-60 uppercase tracking-widest italic">Live processing active</p>
            </div>
        </div>

        {{-- Orders Table Card --}}
        <div class="glass-card rounded-[3rem] shadow-2xl border border-white/5 overflow-hidden fade-in-up" style="animation-delay: 0.2s">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Order Details</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Customer</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Status</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Bill Amount</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($orders as $index => $order)
                            {{-- Staggered Entry using Inline Delay --}}
                            <tr class="group hover:bg-white/[0.03] transition-all duration-300"
                                x-show="show"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                style="display: none; transition-delay: {{ $index * 50 }}ms">
                                <td class="px-10 py-8">
                                    <span class="font-black text-white tracking-tighter text-lg">#{{ $order->id }}</span>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $order->order_date->format('d M, Y') }}</p>
                                </td>
                                <td class="px-10 py-8">
                                    <p class="font-black text-slate-200">{{ $order->user->name }}</p>
                                    <p class="text-[10px] text-indigo-400 font-bold tracking-widest uppercase mt-1">{{ $order->user->email }}</p>
                                </td>
                                <td class="px-10 py-8 text-center">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                            'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                            'cancelled' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                        ];
                                        $currentStyle = $statusStyles[$order->status] ?? 'bg-white/5 text-slate-400 border-white/10';
                                    @endphp
                                    <span class="inline-block px-5 py-1.5 rounded-full border {{ $currentStyle }} text-[9px] font-black uppercase tracking-[0.2em] shadow-inner">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-10 py-8">
                                    <p class="font-black text-white text-lg tracking-tighter">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $order->courier }}</p>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-white text-slate-950 hover:bg-indigo-600 hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-90 shadow-lg shadow-white/5">
                                        Open Record
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr x-show="show" x-transition:enter="transition ease-out duration-700 delay-300">
                                <td colspan="5" class="px-10 py-24 text-center">
                                    <div class="text-6xl mb-6 opacity-20 grayscale">📦</div>
                                    <p class="text-slate-500 font-black uppercase tracking-[0.3em] text-sm animate-bounce">Zero Orders Found</p>
                                    <p class="text-slate-600 text-xs mt-2 font-medium">Sistem sedang menunggu transaksi baru masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Styling --}}
            <div class="px-10 py-8 bg-white/[0.01] border-t border-white/5">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
