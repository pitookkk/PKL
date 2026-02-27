@extends('layouts.app')

@section('title', 'Send Broadcast - Pitocom Admin')

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
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" x-data="{ initObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
}}" x-init="initObserver()">
    
    {{-- CRM Navigation --}}
    <div class="reveal-section mb-10 text-left">
        @include('admin.crm.partials.navbar')
    </div>

    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase text-left">Promotional <span class="text-sky-500">Broadcast.</span></h1>
        <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1 text-left">Kirim pesan WhatsApp massal ke pelanggan yang tidak aktif dalam 6 bulan terakhir.</p>
        <div class="h-1 w-20 bg-sky-500 mt-5 rounded-full text-left"></div>
    </div>

    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-16 relative overflow-hidden group text-left">
        {{-- Decorative Glow Background --}}
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000 text-left"></div>

        <form action="{{ route('admin.crm.broadcast.send') }}" method="POST" class="relative text-left">
            @csrf
            <div class="space-y-10 text-left">
                <div class="text-left">
                    <div class="flex items-center mb-6 text-left">
                        <div class="bg-emerald-50 text-emerald-600 p-3 rounded-2xl mr-4 shadow-sm border border-emerald-100 text-left">
                            <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <label for="message" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] text-left">Message Engine Content</label>
                    </div>
                    
                    <textarea name="message" id="message" rows="8" 
                              class="w-full px-10 py-8 bg-slate-50 border-none rounded-[2.5rem] focus:ring-2 focus:ring-emerald-500 font-bold text-slate-700 text-lg transition-all placeholder-slate-300 shadow-inner leading-relaxed text-left" 
                              required placeholder="Contoh: Halo Sobat Pitocom! Sudah lama tidak merakit PC? Dapatkan promo pembersihan hardware gratis khusus untuk Anda!">{{ old('message') }}</textarea>
                    
                    {{-- Warning Hub --}}
                    <div class="mt-8 p-6 bg-amber-50/50 rounded-3xl border border-amber-100 flex items-start text-left">
                        <div class="bg-white p-2 rounded-xl shadow-sm mr-4 text-left">
                            <svg class="w-5 h-5 text-amber-500 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest text-left">System Intelligence Note</p>
                            <p class="text-xs text-amber-700/60 font-bold mt-1 leading-relaxed text-left">
                                Pesan akan dikirimkan secara mentah. Token personalisasi seperti <span class="bg-amber-100/50 px-1.5 py-0.5 rounded text-amber-800">[Nama Pelanggan]</span> saat ini belum aktif dan akan terkirim sesuai teks tersebut.
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Action Hub --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-10 border-t border-slate-50 text-left">
                    <div class="flex items-center text-left">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 mr-3 animate-pulse shadow-[0_0_12px_rgba(16,185,129,0.4)]"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-left">Target: <span class="text-slate-900">Inactive Users (6 Months)</span></p>
                    </div>
                    
                    <div class="flex w-full md:w-auto gap-4">
                        <a href="{{ route('admin.crm.index') }}" 
                           class="flex-1 md:flex-none px-10 py-5 bg-slate-50 text-slate-400 rounded-3xl font-black uppercase tracking-widest hover:bg-slate-100 transition-all text-[10px] flex justify-center items-center">
                            Abort Session
                        </a>
                        <button type="submit" 
                                class="flex-1 md:flex-none px-12 py-5 bg-slate-900 text-white rounded-3xl font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-2xl shadow-slate-200 active:scale-95 text-[10px] flex justify-center items-center">
                            Launch Broadcast Now
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection