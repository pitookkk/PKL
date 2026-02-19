@extends('layouts.app')

@section('title', 'Manage Orders - Pitocom Admin')

@section('content')
    {{-- Memastikan Navbar Admin Terpanggil --}}
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-4 py-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 animate-fade-in-up">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Customer Orders</h1>
                <p class="text-slate-500 font-medium">Pantau dan kelola seluruh transaksi masuk secara real-time.</p>
            </div>
            <div class="flex space-x-3">
                <button class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                    Export Data
                </button>
            </div>
        </div>

        {{-- Bento Grid Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Pending</p>
                <h3 class="text-3xl font-black text-amber-500 mt-1">{{ $orders->where('status', 'pending')->count() }}</h3>
            </div>
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Completed</p>
                <h3 class="text-3xl font-black text-emerald-500 mt-1">{{ $orders->where('status', 'completed')->count() }}</h3>
            </div>
            <div class="bg-slate-900 p-6 rounded-[2.5rem] shadow-xl text-white md:col-span-2 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-sky-500/20 rounded-full blur-3xl"></div>
                <p class="text-sky-400 text-[10px] font-bold uppercase tracking-widest">Total Revenue</p>
                <h3 class="text-2xl font-black mt-1">Rp {{ number_format($orders->where('status', 'completed')->sum('total_amount'), 0, ',', '.') }}</h3>
                <p class="text-slate-400 text-xs mt-1 italic">*Berdasarkan pesanan yang telah selesai.</p>
            </div>
        </div>

        {{-- Orders Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Order ID</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Customer</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Total Bill</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($orders as $order)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="font-black text-slate-900 tracking-tighter">#{{ $order->id }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-bold text-slate-800">{{ $order->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium tracking-wide lowercase">{{ $order->user->email }}</p>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                    {{ $order->order_date->format('d M Y') }}
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                    @endphp
                                    <span class="inline-block px-4 py-1 rounded-full border {{ $currentStyle }} text-[10px] font-black uppercase tracking-widest">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 font-black text-slate-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center px-5 py-2 bg-slate-100 hover:bg-slate-900 hover:text-white text-slate-600 text-xs font-bold rounded-xl transition-all active:scale-95">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="text-5xl mb-4">📦</div>
                                    <p class="text-slate-400 font-bold text-lg">No orders found.</p>
                                    <p class="text-slate-300 text-sm mt-1">Belum ada transaksi yang masuk hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Styling --}}
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection