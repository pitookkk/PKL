@extends('layouts.app')

@section('title', 'Customer Management - Pitocom Admin')

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

    {{-- Header Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Customer <span class="text-sky-500">Management</span></h1>
        <p class="text-slate-500 mt-2 font-medium text-lg">Kelola data pelanggan, pantau loyalitas, dan lihat riwayat belanja Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    <div class="mb-10 bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <form action="{{ route('admin.crm.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            <div>
                <label for="level" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Membership Level</label>
                <select name="level" id="level" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all cursor-pointer">
                    <option value="">All Levels</option>
                    <option value="Silver" @selected(isset($filters['level']) && $filters['level'] == 'Silver')>Silver Member</option>
                    <option value="Gold" @selected(isset($filters['level']) && $filters['level'] == 'Gold')>Gold Member</option>
                    <option value="Platinum" @selected(isset($filters['level']) && $filters['level'] == 'Platinum')>Platinum Member</option>
                </select>
            </div>
            <div>
                <label for="sort_spent" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Sort by Spending</label>
                <select name="sort_spent" id="sort_spent" class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all cursor-pointer">
                    <option value="">Default (Terbaru)</option>
                    <option value="desc" @selected(isset($filters['sort_spent']) && $filters['sort_spent'] == 'desc')>High to Low (Sultan)</option>
                    <option value="asc" @selected(isset($filters['sort_spent']) && $filters['sort_spent'] == 'asc')>Low to High</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-8 py-3 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-lg shadow-slate-200 active:scale-95 text-sm">
                    Apply Filter
                </button>
                <a href="{{ route('admin.crm.index') }}" class="px-6 py-3 bg-slate-100 text-slate-400 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-sm flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>


    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Customer</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Membership</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Spent</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Loyalty Points</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-sky-500 flex items-center justify-center font-black text-white text-sm shadow-sm shadow-sky-100 mr-4">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">{{ $customer->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-bold tracking-tight">{{ $customer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @php
                                    $level = $customer->membership_level;
                                    $badgeStyle = match($level) {
                                        'Platinum' => 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                        'Gold' => 'bg-amber-100 text-amber-600 border-amber-200',
                                        default => 'bg-slate-100 text-slate-400 border-slate-200',
                                    };
                                @endphp
                                <span class="px-4 py-1.5 text-[10px] font-black rounded-full border {{ $badgeStyle }} uppercase tracking-widest shadow-sm">
                                    {{ $level }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm font-black text-emerald-500 tracking-tight text-left">
                                Rp {{ number_format($customer->total_spent, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-black text-slate-700 mr-2">{{ number_format($customer->points, 0, ',', '.') }}</span>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-tighter">Pts</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.crm.customer360', $customer) }}" 
                                   class="inline-flex items-center px-6 py-2 bg-slate-50 text-sky-500 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-sky-500 hover:text-white transition-all shadow-sm">
                                    View 360&deg; Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-bold italic bg-slate-50/20">
                                <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Data pelanggan tidak ditemukan dengan filter tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination bergaya Pitocom --}}
    <div class="mt-10">
        {{ $customers->links() }}
    </div>

</div>
@endsection