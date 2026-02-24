@extends('layouts.app')

@section('title', 'Customer 360 - ' . $user->name)

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- CRM Navigation --}}
    <div class="mb-10">
        @include('admin.crm.partials.navbar')
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 mb-10 relative overflow-hidden group">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8 text-left">
            <div class="flex items-center">
                <div class="h-20 w-20 rounded-[2rem] bg-slate-900 flex items-center justify-center font-black text-white text-3xl shadow-xl shadow-slate-200 mr-6">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                    <p class="text-slate-400 font-bold mt-0.5 tracking-tight">{{ $user->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="bg-sky-50 text-sky-500 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-sky-100 italic">
                            Joined {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 md:gap-12">
                <div class="text-left">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Spent</p>
                    <p class="text-xl font-black text-emerald-500 tracking-tighter">Rp {{ number_format($user->orders->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="text-left border-l border-slate-50 pl-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Points</p>
                    <p class="text-xl font-black text-sky-500 tracking-tighter">{{ number_format($user->loyaltyPoint->points_balance ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="text-left border-l border-slate-50 pl-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Orders</p>
                    <p class="text-xl font-black text-slate-900 tracking-tighter">{{ $user->orders->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-10">
            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-left">
                <div class="flex items-center mb-8">
                    <div class="bg-sky-500 p-2.5 rounded-xl mr-4 shadow-sm shadow-sky-100">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Interaction Logs</h2>
                </div>
                
                {{-- Form Log Baru --}}
                <form action="{{ route('admin.crm.logInteraction', $user) }}" method="POST" class="mb-10 bg-slate-50 p-6 rounded-3xl border border-slate-100 transition-all focus-within:shadow-md">
                    @csrf
                    <textarea name="note" rows="2" required 
                              class="w-full bg-transparent border-none focus:ring-0 font-bold text-slate-700 placeholder-slate-300" 
                              placeholder="Catat interaksi (Contoh: 'Telfon konfirmasi stok RTX 5090')"></textarea>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-sky-500 transition-all shadow-lg active:scale-95">
                            Add Log Entry
                        </button>
                    </div>
                </form>

                {{-- Timeline Logs --}}
                <div class="space-y-6 relative ml-4">
                    {{-- Timeline Line --}}
                    <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-slate-100 -ml-4"></div>

                    @forelse ($user->interactionLogs->sortByDesc('created_at') as $log)
                        <div class="relative pl-6">
                            {{-- Timeline Dot --}}
                            <div class="absolute left-0 top-1.5 w-2 h-2 rounded-full bg-sky-500 -ml-[1.25rem] border-2 border-white ring-4 ring-sky-50"></div>
                            
                            <p class="text-slate-700 font-bold leading-relaxed text-sm">{{ $log->note }}</p>
                            <div class="flex items-center mt-2 text-[10px] font-black uppercase tracking-tighter text-slate-400">
                                <span class="text-sky-500 mr-2">{{ $log->admin->name ?? 'N/A' }}</span>
                                <span>•</span>
                                <span class="ml-2 italic">{{ $log->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 font-bold italic text-sm py-4">Belum ada catatan interaksi.</p>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-left">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Hardware Library</h3>
                    <div class="space-y-3">
                        {{-- Data Hardware User --}}
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-sky-200 transition-colors cursor-default">
                             <p class="text-sm font-black text-slate-900 tracking-tight">Gaming PC Custom Build</p>
                             <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">SN: PITO-2026-X88</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-left">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Active Tickets</h3>
                    <div class="space-y-3 text-center py-6">
                        <svg class="w-8 h-8 mx-auto text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[10px] font-black text-slate-300 uppercase italic">No Active Complaints</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-left h-fit sticky top-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Order History</h2>
                    <span class="bg-slate-100 text-slate-400 px-3 py-1 rounded-lg text-[10px] font-black">{{ $user->orders->count() }} Items</span>
                </div>

                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                    @forelse ($user->orders as $order)
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-50 group hover:bg-white hover:border-sky-100 transition-all duration-300">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-sm font-black text-slate-900 group-hover:text-sky-500 transition-colors">#{{ $order->id }}</p>
                                <span class="px-3 py-1 text-[8px] font-black rounded-full uppercase tracking-tighter {{ $order->status == 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $order->order_date->format('d M, Y') }}</p>
                            <p class="mt-4 text-sm font-black text-slate-800 tracking-tighter">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    @empty
                         <p class="text-slate-400 font-bold italic text-center py-10">No orders found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection