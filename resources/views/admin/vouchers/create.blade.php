@extends('layouts.app')

@section('title', 'Create New Voucher')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
<div class="max-w-4xl mx-auto px-4 py-12">
    
    <div class="mb-10 text-left">
        <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-bold text-sky-500 hover:underline">&larr; Back to List</a>
        <h1 class="text-4xl font-black text-slate-900 mt-2 tracking-tight">Create <span class="text-sky-500">New Voucher</span></h1>
        <p class="text-slate-500 mt-1 font-medium">Buat kode promo untuk meningkatkan konversi penjualan.</p>
    </div>

    <form action="{{ route('admin.vouchers.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-8 text-left">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Code --}}
                <div>
                    <label for="code" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Voucher Code</label>
                    <input type="text" name="code" id="code" required value="{{ old('code') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 uppercase"
                           placeholder="e.g. PITOCOM2026">
                    @error('code') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Expiry --}}
                <div>
                    <label for="expires_at" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Expiry Date (Optional)</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Type --}}
                <div>
                    <label for="type" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Discount Type</label>
                    <select name="type" id="type" required 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                        <option value="fixed">Fixed Amount (IDR)</option>
                        <option value="percent">Percentage (%)</option>
                    </select>
                </div>

                {{-- Value --}}
                <div>
                    <label for="value" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Discount Value</label>
                    <input type="number" name="value" id="value" required value="{{ old('value') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600">
                </div>

                {{-- Max Uses --}}
                <div>
                    <label for="max_uses" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Max Total Usage</label>
                    <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700"
                           placeholder="Unlimited if empty">
                </div>
            </div>

            {{-- Min Spend --}}
            <div>
                <label for="min_spend" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Minimum Spend (Rp)</label>
                <input type="number" name="min_spend" id="min_spend" required value="{{ old('min_spend', 0) }}" 
                       class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-[0.98]">
                    Create Voucher
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
