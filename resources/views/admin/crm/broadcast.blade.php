@extends('layouts.app')

@section('title', 'Send Broadcast - Pitocom Admin')

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
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Promotional <span class="text-sky-500">Broadcast</span></h1>
        <p class="text-slate-500 mt-2 font-medium text-lg italic">Kirim pesan WhatsApp massal ke pelanggan yang tidak aktif dalam 6 bulan terakhir.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 relative overflow-hidden group text-left">
        {{-- Decorative Glow --}}
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

        <form action="{{ route('admin.crm.broadcast.send') }}" method="POST">
            @csrf
            <div class="space-y-8 relative">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="bg-emerald-100 text-emerald-600 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <label for="message" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Broadcast Message Content</label>
                    </div>
                    
                    <textarea name="message" id="message" rows="8" 
                              class="w-full px-8 py-6 bg-slate-50 border-none rounded-[2rem] focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all placeholder-slate-300 shadow-inner" 
                              required placeholder="Contoh: Halo Sobat Pitocom! Sudah lama tidak merakit PC? Dapatkan promo pembersihan hardware gratis khusus untuk Anda!">{{ old('message') }}</textarea>
                    
                    <div class="mt-4 p-5 bg-amber-50 rounded-2xl border border-amber-100 flex items-start">
                        <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Informasi Sistem</p>
                            <p class="text-xs text-amber-700/70 font-bold mt-1 leading-relaxed">
                                Pesan akan dikirimkan secara mentah. Token personalisasi seperti <span class="bg-amber-100 px-1.5 py-0.5 rounded text-amber-800">[Nama Pelanggan]</span> belum didukung dan akan terkirim sesuai tulisan tersebut.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-6 border-t border-slate-50">
                    <div class="flex items-center">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Target: Inactive Users (6 Months)</p>
                    </div>
                    
                    <div class="flex w-full md:w-auto gap-3">
                        <a href="{{ route('admin.crm.index') }}" 
                           class="flex-1 md:flex-none px-8 py-4 bg-slate-100 text-slate-400 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-xs text-center">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="flex-1 md:flex-none px-10 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-xl shadow-slate-200 active:scale-95 text-xs">
                            Launch Broadcast Now
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection