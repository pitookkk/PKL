@extends('layouts.app')

@section('title', 'Manage Orders - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    /* Animasi Section Reveal */
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        show: false,
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        }
     }" x-init="initObserver(); setTimeout(() => show = true, 100)">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase text-left">Customer <span class="text-indigo-600">Orders.</span></h1>
                <p class="text-slate-500 mt-2 font-medium text-lg italic text-left pl-1">Pantau dan kelola seluruh transaksi masuk secara real-time.</p>
                <div class="h-1.5 w-24 bg-indigo-500 mt-5 rounded-full text-left text-left"></div>
            </div>
            <button class="bg-slate-900 text-white font-black px-10 py-5 rounded-[1.8rem] shadow-2xl shadow-slate-200 hover:bg-indigo-600 transition-all active:scale-95 text-xs uppercase tracking-widest shrink-0 text-left text-left">
                Export Logistics
            </button>
        </div>
    </div>

    {{-- Bento Grid Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
        {{-- Pending --}}
        <div class="reveal-section bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm group relative overflow-hidden" style="transition-delay: 100ms">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] text-left text-left">Waiting Action</p>
            <h3 class="text-5xl font-black text-amber-500 mt-3 tracking-tighter text-left text-left">{{ $orders->where('status', 'pending')->count() }}</h3>
            <p class="text-[9px] text-amber-500/50 font-bold uppercase mt-2 text-left">Pending Orders</p>
        </div>

        {{-- Completed --}}
        <div class="reveal-section bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm group relative overflow-hidden" style="transition-delay: 200ms">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] text-left">Successful Delivery</p>
            <h3 class="text-5xl font-black text-emerald-500 mt-3 tracking-tighter text-left">{{ $orders->where('status', 'completed')->count() }}</h3>
            <p class="text-[9px] text-emerald-500/50 font-bold uppercase mt-2 text-left">Completed Orders</p>
        </div>

        {{-- Revenue --}}
        <div class="reveal-section bg-indigo-600 p-8 rounded-[3rem] shadow-2xl shadow-indigo-200 text-white md:col-span-2 relative overflow-hidden group" style="transition-delay: 300ms">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            <p class="text-indigo-200 text-[10px] font-black uppercase tracking-[0.2em] text-left">Total Gross Revenue</p>
            <h3 class="text-4xl font-black mt-3 tracking-tighter text-left">Rp{{ number_format($orders->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h3>
            <div class="flex items-center mt-3 text-left">
                <div class="w-1.5 h-1.5 bg-indigo-300 rounded-full animate-pulse mr-2"></div>
                <p class="text-indigo-200 text-[9px] font-bold uppercase tracking-widest">Live processing active</p>
            </div>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20" style="transition-delay: 400ms">
        <div class="overflow-x-auto no-scrollbar text-left text-left">
            <table class="w-full text-left table-fixed">
                <thead class="bg-slate-50/50 text-left text-left">
                    <tr>
                        <th class="w-48 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left text-left text-left">Order Details</th>
                        <th class="w-72 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left text-left text-left">Customer Assets</th>
                        <th class="w-40 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-center text-left text-left">State</th>
                        <th class="w-48 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-left text-left text-left">Bill Amount</th>
                        <th class="w-44 px-10 py-7 text-[11px] font-black text-slate-400 uppercase tracking-[0.25em] text-right text-left text-left">Ops Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-left text-left">
                    @forelse ($orders as $index => $order)
                        <tr class="hover:bg-slate-50/30 transition-all group text-left text-left"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            style="display: none; transition-delay: {{ $index * 50 }}ms">
                            
                            {{-- ID & Date --}}
                            <td class="px-10 py-8 text-left text-left">
                                <span class="font-black text-slate-900 tracking-tighter text-xl text-left text-left">#{{ $order->id }}</span>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-1.5 text-left text-left text-left">{{ $order->order_date->format('d M, Y') }}</p>
                            </td>

                            {{-- User Info --}}
                            <td class="px-10 py-8 text-left text-left">
                                <p class="font-black text-slate-800 text-base text-left text-left">{{ $order->user->name }}</p>
                                <p class="text-[10px] text-indigo-500 font-black tracking-widest uppercase mt-1 text-left text-left text-left">{{ $order->user->email }}</p>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-10 py-8 text-center text-left text-left">
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-500 border-amber-100',
                                        'completed' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
                                        'cancelled' => 'bg-rose-50 text-rose-500 border-rose-100',
                                    ];
                                    $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-400 border-slate-100';
                                @endphp
                                <span class="inline-block px-5 py-2 rounded-full border {{ $currentStyle }} text-[9px] font-black uppercase tracking-[0.2em] shadow-sm text-center">
                                    {{ $order->status }}
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td class="px-10 py-8 text-left text-left">
                                <p class="font-black text-slate-900 text-lg tracking-tighter text-left text-left">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1 text-left text-left text-left text-left">{{ $order->courier }} Service</p>
                            </td>

                            {{-- Action --}}
                            <td class="px-10 py-8 text-right text-left text-left text-left">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-slate-900 text-white hover:bg-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-90 shadow-xl shadow-slate-200 text-left text-left text-left text-left text-left">
                                    Open Record
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center text-left text-left">
                                <div class="text-6xl mb-6 opacity-20 grayscale text-left text-left">📦</div>
                                <p class="text-slate-300 font-black uppercase tracking-[0.3em] text-sm text-left text-left">Database Order Kosong</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50 text-left text-left">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection