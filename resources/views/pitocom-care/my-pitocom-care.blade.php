@extends('layouts.app')

@section('title', 'My Pitocom Care')

@section('content')
{{-- Load Font Premium --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ isModalOpen: false }">
    
    {{-- Header Section - Menyesuaikan gaya teks tebal Pitocom --}}
    <div class="mb-12 text-left animate-fade-in-up">
        <h1 class="text-5xl font-black text-slate-900 tracking-tight">My Pitocom <span class="text-sky-500">Care</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic">Pusat bantuan hardware, membership, dan reward personal Anda.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        {{-- Main Content (Left/Center) --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- 1. Submit Ticket Form - Menggunakan desain Rounded-3xl dan shadow Pitocom --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <div class="flex items-center mb-6">
                    <div class="bg-sky-500 p-3 rounded-2xl mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Create Support Ticket</h2>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Sampaikan keluhan atau pertanyaan teknis Anda</p>
                    </div>
                </div>
                
                <form action="{{ route('support-tickets.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Subject</label>
                            <input type="text" name="subject" required placeholder="Contoh: Overheating, BSOD, atau Masalah Komponen"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Masalah</label>
                            <textarea name="description" rows="4" required placeholder="Jelaskan detail masalah PC Anda secara lengkap..."
                                      class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Tingkat Prioritas</label>
                                <select name="priority" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 cursor-pointer transition-all">
                                    <option value="low">Low - General Inquiry</option>
                                    <option value="medium" selected>Medium - Minor Issue</option>
                                    <option value="high">High - Critical Issue</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-lg shadow-slate-200 active:scale-95">
                                    Submit Ticket
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- 2. Ticket History - List bergaya minimalis Pitocom --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10" x-data="{ tickets: {{ json_encode($user->serviceTickets) }} }">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Ticket History</h2>
                    <span class="bg-slate-100 text-slate-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest" x-text="tickets.length + ' Tickets'"></span>
                </div>

                <div class="space-y-4">
                    <template x-if="tickets.length === 0">
                        <div class="text-center py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            <p class="text-slate-400 font-bold italic">Belum ada riwayat tiket bantuan.</p>
                        </div>
                    </template>
                    <template x-for="ticket in tickets" :key="ticket.id">
                        <div class="group border border-slate-100 rounded-3xl p-6 hover:bg-slate-50 transition-all cursor-pointer">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <p class="font-black text-slate-900 text-lg group-hover:text-sky-500 transition-colors" x-text="ticket.subject"></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                        #<span x-text="ticket.ticket_number"></span> • Created: <span x-text="new Date(ticket.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})"></span>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                     <span class="px-4 py-1.5 text-[10px] font-black rounded-full uppercase tracking-tighter shadow-sm"
                                          :class="{
                                            'bg-blue-100 text-blue-600': ticket.status === 'open',
                                            'bg-amber-100 text-amber-600': ticket.status === 'processing',
                                            'bg-emerald-100 text-emerald-600': ticket.status === 'finished',
                                            'bg-slate-100 text-slate-400': ticket.status === 'closed'
                                          }"
                                          x-text="ticket.status">
                                    </span>
                                    <span class="px-4 py-1.5 text-[10px] font-black rounded-full uppercase tracking-tighter"
                                          :class="{
                                            'bg-rose-100 text-rose-600': ticket.priority === 'high',
                                            'bg-slate-100 text-slate-600': ticket.priority === 'medium',
                                            'bg-slate-50 text-slate-400': ticket.priority === 'low'
                                          }"
                                          x-text="ticket.priority">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Sidebar (Right) --}}
        <div class="space-y-10">
            {{-- Membership & Points - Bergaya Card Flash Sale --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-center relative overflow-hidden group">
                {{-- Decorative element --}}
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">My Membership</h3>
                
                @php
                    $level = $user->membership_level;
                    $colors = [
                        'Silver' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-400', 'accent' => 'bg-slate-400'],
                        'Gold' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-500', 'accent' => 'bg-amber-500'],
                        'Platinum' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'accent' => 'bg-indigo-600'],
                    ];
                    $style = $colors[$level] ?? $colors['Silver'];
                @endphp
                
                <div class="inline-flex items-center px-6 py-2 rounded-2xl {{ $style['bg'] }} mb-8 border-2 border-white shadow-sm">
                    <div class="w-2 h-2 rounded-full {{ $style['accent'] }} mr-3 animate-pulse"></div>
                    <p class="font-black {{ $style['text'] }} uppercase tracking-widest text-[10px]">{{ $level }} Member</p>
                </div>
                
                <div class="mb-2">
                    <span class="text-6xl font-black text-slate-900 tracking-tighter">{{ number_format($user->points, 0, ',', '.') }}</span>
                </div>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8">Loyalty Points</p>
                
                <div class="pt-8 border-t border-slate-50">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Lifetime Spending</p>
                    <p class="text-2xl font-black text-emerald-500 tracking-tight">Rp {{ number_format($user->total_spent, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- My Hardware --}}
             <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                <div class="flex items-center mb-6">
                    <svg class="w-5 h-5 text-sky-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                    <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">My Hardware</h3>
                </div>
                <div class="space-y-4">
                    @forelse ($user->customerAssets as $asset)
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                            <p class="font-black text-slate-900 text-sm tracking-tight">{{ $asset->device_name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Purchased: {{ $asset->purchase_date->format('d M, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 font-medium italic text-sm text-center py-4">Belum ada hardware terdaftar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection