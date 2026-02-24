@extends('layouts.app')

@section('title', 'Voucher Management')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-10 text-left">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Voucher & Promo</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage discount codes and special marketing campaigns.</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="bg-slate-900 text-white font-black px-6 py-3 rounded-2xl shadow-xl hover:bg-sky-500 transition-all active:scale-95">
            + Create New Voucher
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-2xl">
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Voucher Code</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Value</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Usage</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($vouchers as $v)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="flex items-center">
                                    <div class="bg-sky-50 text-sky-600 px-3 py-1 rounded-lg font-black text-sm border border-sky-100">
                                        {{ $v->code }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Expires</div>
                                        <div class="text-xs font-semibold text-slate-600">
                                            {{ $v->expires_at ? $v->expires_at->format('d M Y') : 'Never' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-sm font-black text-slate-900">
                                    @if($v->type === 'percent')
                                        {{ number_format($v->value, 0) }}% OFF
                                    @else
                                        Rp {{ number_format($v->value, 0, ',', '.') }} OFF
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold">Min Spend: Rp {{ number_format($v->min_spend, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-sm font-bold text-slate-700">
                                    {{ $v->used_count }} / {{ $v->max_uses ?? '∞' }}
                                </div>
                                <div class="w-24 bg-slate-100 h-1.5 rounded-full mt-1 overflow-hidden" 
                                     style="--progress-width: {{ $v->max_uses ? ($v->used_count / $v->max_uses) * 100 : 0 }}%">
                                    <div class="bg-sky-500 h-full" style="width: var(--progress-width)"></div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                @php
                                    $isExpired = $v->expires_at && $v->expires_at->isPast();
                                @endphp
                                <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest 
                                    {{ ($v->is_active && !$isExpired) ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">
                                    {{ $isExpired ? 'Expired' : ($v->is_active ? 'Active' : 'Inactive') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.vouchers.edit', $v) }}" class="text-xs font-black text-sky-500 hover:text-sky-700 uppercase tracking-widest transition-colors">Edit</a>
                                    <form action="{{ route('admin.vouchers.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Hapus voucher ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-medium italic">Belum ada voucher yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
