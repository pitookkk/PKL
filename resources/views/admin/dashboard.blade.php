@extends('layouts.app')

@section('title', 'Admin Dashboard - Pitocom')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    {{-- Header Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Admin <span class="text-sky-500">Dashboard</span></h1>
        <p class="text-slate-500 mt-2 font-medium text-lg">Ringkasan performa toko, data pengguna, dan pesanan terbaru Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>
    
    {{-- Stats Cards - Bergaya Kartu Flash Sale --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        {{-- Total Products --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 bg-sky-500/5 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 relative">Total Products</h3>
            <p class="text-4xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['products_count']) }}</p>
            <div class="mt-4 flex items-center text-sky-500 font-bold text-[10px] uppercase tracking-widest relative">
                <span>View Inventory</span>
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        {{-- Total Categories --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-xl hover:-translate-y-1 text-left">
            <div class="absolute -right-4 -top-4 bg-amber-500/5 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-700 text-left"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 relative text-left">Total Categories</h3>
            <p class="text-4xl font-black text-slate-900 tracking-tighter relative text-left">{{ number_format($stats['categories_count']) }}</p>
            <div class="mt-4 flex items-center text-amber-500 font-bold text-[10px] uppercase tracking-widest relative text-left">
                <span>Manage Tags</span>
                <svg class="w-3 h-3 ml-1 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7 text-left"/></svg>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 bg-emerald-500/5 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 relative">Total Orders</h3>
            <p class="text-4xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['orders_count']) }}</p>
            <div class="mt-4 flex items-center text-emerald-500 font-bold text-[10px] uppercase tracking-widest relative">
                <span>Check Sales</span>
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group transition-all hover:shadow-xl hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 bg-rose-500/5 w-24 h-24 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 relative">Total Users</h3>
            <p class="text-4xl font-black text-slate-900 tracking-tighter relative">{{ number_format($stats['users_count']) }}</p>
            <div class="mt-4 flex items-center text-rose-500 font-bold text-[10px] uppercase tracking-widest relative">
                <span>Manage Customers</span>
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Recent Orders</h2>
            <a href="#" class="text-[10px] font-black text-sky-500 uppercase tracking-widest hover:underline">View All Orders &rarr;</a>
        </div>
        <div class="overflow-x-auto text-left">
            <table class="min-w-full divide-y divide-slate-100 text-left">
                <thead class="bg-slate-50/50 text-left">
                    <tr class="text-left">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Order ID</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customer</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Date</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-left">
                    @forelse ($stats['recent_orders'] as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors text-left">
                            <td class="px-8 py-6 whitespace-nowrap font-black text-slate-900 text-sm text-left">#{{ $order->id }}</td>
                            <td class="px-8 py-6 whitespace-nowrap text-left text-left">
                                <div class="flex items-center text-left">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs mr-3 text-left text-left">
                                        {{ substr($order->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 text-left text-left">{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-[11px] font-bold text-slate-400 uppercase tracking-widest text-left">{{ $order->order_date->format('d M Y') }}</td>
                            <td class="px-8 py-6 whitespace-nowrap text-left text-left">
                                @php
                                    $statusStyle = match($order->status) {
                                        'pending' => 'bg-amber-100 text-amber-600 border-amber-200',
                                        'completed' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                        'cancelled' => 'bg-rose-100 text-rose-600 border-rose-200',
                                        default => 'bg-slate-100 text-slate-400 border-slate-200',
                                    };
                                @endphp
                                <span class="px-4 py-1.5 text-[9px] font-black rounded-full border {{ $statusStyle }} uppercase tracking-widest shadow-sm text-left">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap font-black text-slate-900 tracking-tight text-left">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-6 whitespace-nowrap text-right text-left text-left">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="inline-flex items-center px-6 py-2 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-lg shadow-slate-200 text-left text-left">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-bold italic bg-slate-50/20 text-left">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Belum ada pesanan masuk saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection