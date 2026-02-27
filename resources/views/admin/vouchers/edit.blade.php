@extends('layouts.app')

@section('title', 'Edit Voucher - Pitocom Admin')

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
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" x-data="{ initObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
}}" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <a href="{{ route('admin.vouchers.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-colors mb-6 group text-left">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to Voucher Hub
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase text-left">Modify <span class="text-sky-500">Voucher.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Perbarui aturan diskon, masa berlaku, atau status aktivasi kode promo secara real-time.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="max-w-5xl mx-auto">
        @csrf
        @method('PUT')
        
        <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-16 space-y-12 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 bg-sky-500/5 w-40 h-40 rounded-full group-hover:scale-150 transition-transform duration-1000 text-left"></div>
            
            {{-- Section 1: Credentials --}}
            <div class="space-y-8 relative text-left">
                <div class="flex items-center mb-4 text-left">
                    <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg text-left text-left">
                        <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Voucher Identity</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                    <div class="text-left text-left">
                        <label for="code" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Voucher Code</label>
                        <input type="text" name="code" id="code" required value="{{ old('code', $voucher->code) }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 uppercase shadow-inner text-base tracking-widest transition-all text-left">
                    </div>

                    <div class="text-left text-left">
                        <label for="expires_at" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left">Expiration Timeline</label>
                        <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '') }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 shadow-inner text-base transition-all text-left">
                    </div>
                </div>
            </div>

            {{-- Section 2: Values & Logic --}}
            <div class="space-y-8 relative text-left">
                <div class="flex items-center mb-4 text-left">
                    <div class="bg-amber-100 text-amber-600 p-3 rounded-2xl mr-4 shadow-sm border border-amber-200 text-left">
                        <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Benefit Window</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-left">
                    <div class="text-left text-left">
                        <label for="type" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Discount Mechanic</label>
                        <div class="relative text-left">
                            <select name="type" id="type" required 
                                    class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 shadow-inner appearance-none cursor-pointer text-left">
                                <option value="fixed" @selected($voucher->type === 'fixed')>Fixed Amount (IDR)</option>
                                <option value="percent" @selected($voucher->type === 'percent')>Percentage (%)</option>
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-slate-300 text-left">
                                <svg class="h-5 w-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="text-left">
                        <label for="value" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Discount Power</label>
                        <input type="number" name="value" id="value" required value="{{ old('value', $voucher->value) }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 shadow-inner text-xl text-left">
                    </div>

                    <div class="text-left">
                        <label for="max_uses" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Usage Quota</label>
                        <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses', $voucher->max_uses) }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 shadow-inner text-base text-left">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                    <div class="text-left">
                        <label for="min_spend" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Minimum Transaction (Rp)</label>
                        <input type="number" name="min_spend" id="min_spend" required value="{{ old('min_spend', $voucher->min_spend) }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 shadow-inner text-lg text-left">
                    </div>

                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-inner flex items-center justify-between text-left">
                        <div class="text-left">
                            <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest text-left">Activation State</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1 text-left">Currently: <span class="{{ $voucher->is_active ? 'text-emerald-500' : 'text-rose-500' }}">{{ $voucher->is_active ? 'ACTIVE' : 'DISABLED' }}</span></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer text-left">
                            <input type="checkbox" name="is_active" value="1" @checked($voucher->is_active) class="sr-only peer text-left">
                            <div class="w-14 h-7 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-sky-500 shadow-inner text-left"></div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Submit Session --}}
            <div class="pt-10 border-t border-slate-50 space-y-4 text-left">
                <button type="submit" class="w-full py-6 bg-sky-500 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] shadow-2xl shadow-sky-200 hover:bg-slate-900 transition-all active:scale-[0.97] text-xs flex justify-center items-center">
                    Sync Changes to Database
                </button>
                <a href="{{ route('admin.vouchers.index') }}" class="block w-full text-center py-5 bg-slate-50 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] rounded-[2.5rem] hover:bg-slate-200 transition-all">
                    Discard Changes
                </a>
            </div>
        </div>
    </form>
</div>
@endsection