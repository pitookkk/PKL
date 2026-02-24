@extends('layouts.app')

@section('title', 'Edit Voucher: ' . $voucher->code)

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
<div class="max-w-4xl mx-auto px-4 py-12">
    
    <div class="mb-10 text-left">
        <a href="{{ route('admin.vouchers.index') }}" class="text-sm font-bold text-sky-500 hover:underline">&larr; Back to List</a>
        <h1 class="text-4xl font-black text-slate-900 mt-2 tracking-tight">Edit <span class="text-sky-500">Voucher</span></h1>
        <p class="text-slate-500 mt-1 font-medium">Perbarui aturan diskon atau status aktivasi voucher.</p>
    </div>

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-8 text-left">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Code --}}
                <div>
                    <label for="code" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Voucher Code</label>
                    <input type="text" name="code" id="code" required value="{{ old('code', $voucher->code) }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 uppercase">
                </div>

                {{-- Expiry --}}
                <div>
                    <label for="expires_at" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Expiry Date</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Type --}}
                <div>
                    <label for="type" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Discount Type</label>
                    <select name="type" id="type" required 
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                        <option value="fixed" @selected($voucher->type === 'fixed')>Fixed Amount (IDR)</option>
                        <option value="percent" @selected($voucher->type === 'percent')>Percentage (%)</option>
                    </select>
                </div>

                {{-- Value --}}
                <div>
                    <label for="value" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Discount Value</label>
                    <input type="number" name="value" id="value" required value="{{ old('value', $voucher->value) }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600">
                </div>

                {{-- Max Uses --}}
                <div>
                    <label for="max_uses" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Max Total Usage</label>
                    <input type="number" name="max_uses" id="max_uses" value="{{ old('max_uses', $voucher->max_uses) }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Min Spend --}}
                <div>
                    <label for="min_spend" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Minimum Spend (Rp)</label>
                    <input type="number" name="min_spend" id="min_spend" required value="{{ old('min_spend', $voucher->min_spend) }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700">
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-sm font-bold text-slate-700">Active Status</p>
                        <p class="text-[10px] text-slate-400 font-medium">Voucher can be used if active</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked($voucher->is_active) class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:outline-none peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-[0.98]">
                    Save Changes
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
