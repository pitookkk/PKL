@extends('layouts.app')

@section('title', 'Admin Dashboard - Pitocom')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; } 
        
        /* Animasi Section Reveal */
        .reveal-section {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        } 
     }" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-5xl font-black text-slate-900 tracking-tight">Admin <span class="text-sky-500">Dashboard.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1">Ringkasan performa toko, data pengguna, dan pesanan terbaru Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
    </div>
    
    {{-- Stats Cards - Link sudah disesuaikan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        {{-- 1. Total Products --}}
        <a href="{{ route('admin.products.index') }}" class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-2xl hover:-translate-y-2 text-left">
            <div class="absolute -right-6 -top-6 bg-sky-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 relative">Total Products</h3>
            <p class="text-5xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['products_count']) }}</p>
            <div class="mt-6 flex items-center text-sky-500 font-black text-[10px] uppercase tracking-widest relative">
                <span>View Inventory</span>
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>

        {{-- 2. Total Categories --}}
        <a href="{{ route('admin.categories.index') }}" class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-2xl hover:-translate-y-2 text-left" style="transition-delay: 100ms">
            <div class="absolute -right-6 -top-6 bg-amber-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 relative">Total Categories</h3>
            <p class="text-5xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['categories_count']) }}</p>
            <div class="mt-6 flex items-center text-amber-500 font-black text-[10px] uppercase tracking-widest relative">
                <span>Manage Tags</span>
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>

        {{-- 3. Total Orders --}}
        <a href="{{ route('admin.orders.index') }}" class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-2xl hover:-translate-y-2 text-left" style="transition-delay: 200ms">
            <div class="absolute -right-6 -top-6 bg-emerald-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 relative">Total Orders</h3>
            <p class="text-5xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['orders_count']) }}</p>
            <div class="mt-6 flex items-center text-emerald-500 font-black text-[10px] uppercase tracking-widest relative">
                <span>Check Sales</span>
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>

        {{-- 4. Total Users --}}
        <a href="{{ route('admin.users.index') }}" class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-2xl hover:-translate-y-2 text-left" style="transition-delay: 300ms">
            <div class="absolute -right-6 -top-6 bg-rose-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 relative">Total Users</h3>
            <p class="text-5xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['users_count']) }}</p>
            <div class="mt-6 flex items-center text-rose-500 font-black text-[10px] uppercase tracking-widest relative">
                <span>Manage Customers</span>
                <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>
    </div>

    {{-- Recent Orders Table --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm overflow-hidden border border-slate-100" style="transition-delay: 400ms">
        <div class="p-10 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Recent Orders</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Transaksi terakhir yang masuk ke sistem</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-8 py-3 bg-slate-50 text-slate-400 hover:text-sky-500 hover:bg-sky-50 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all">
                View Full Logs <svg class="w-3 h-3 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        
        <div class="overflow-x-auto text-left">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-10 py-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">ID</th>
                        <th class="px-10 py-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Customer Info</th>
                        <th class="px-10 py-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Date</th>
                        <th class="px-10 py-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Order Status</th>
                        <th class="px-10 py-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Grand Total</th>
                        <th class="px-10 py-6 text-right text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($stats['recent_orders'] as $order)
                        <tr class="hover:bg-slate-50/30 transition-all group">
                            <td class="px-10 py-7 whitespace-nowrap font-black text-slate-900 text-sm">#{{ $order->id }}</td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs mr-4 shadow-inner border border-slate-200/50 group-hover:bg-sky-500 group-hover:text-white transition-all">
                                        {{ substr($order->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-black text-slate-800 tracking-tight uppercase">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ $order->order_date->format('d M Y') }}</td>
                            <td class="px-10 py-7 whitespace-nowrap">
                                @php
                                    $statusStyle = match($order->status) {
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default => 'bg-slate-50 text-slate-400 border-slate-100',
                                    };
                                @endphp
                                <span class="px-5 py-2 text-[10px] font-black rounded-full border {{ $statusStyle }} uppercase tracking-widest shadow-sm">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-10 py-7 whitespace-nowrap font-black text-slate-900 text-base tracking-tighter">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-10 py-7 whitespace-nowrap text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="inline-flex items-center px-6 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center">
                                <div class="bg-slate-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-pulse">
                                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <p class="text-slate-400 font-black uppercase tracking-widest">No Recent Activity</p>
                                <p class="text-slate-300 text-sm mt-1 italic font-medium pl-1">Belum ada pesanan masuk saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection