@extends('layouts.app')

@section('title', 'Voucher Hub - Pitocom Admin')

@section('content')
    {{-- Load SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('admin.partials.nav')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible { opacity: 1; transform: translateY(0); }

    /* Custom Styling agar SweetAlert mirip screenshot Anda */
    .swal2-popup { border-radius: 3rem !important; padding: 3rem !important; }
    .swal2-title { font-weight: 900 !important; text-transform: none !important; font-size: 2.2rem !important; color: #0f172a !important; }
    .swal2-html-container { font-weight: 600 !important; color: #64748b !important; line-height: 1.6 !important; }
    .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1.2rem !important; padding: 1.2rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
    .swal2-styled.swal2-cancel { background-color: #f1f5f9 !important; color: #94a3b8 !important; border-radius: 1.2rem !important; padding: 1.2rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="voucherManager()" 
     x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Voucher <span class="text-sky-500">& Promo.</span></h1>
                <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1">Hardware Promo Management System</p>
                <div class="h-1.5 w-20 bg-sky-500 mt-5 rounded-full"></div>
            </div>
            <a href="{{ route('admin.vouchers.create') }}" class="bg-slate-900 text-white font-black px-10 py-5 rounded-[1.8rem] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95 text-xs uppercase tracking-widest">
                + Create New Voucher
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="reveal-section bg-emerald-50 border border-emerald-100 text-emerald-700 p-5 mb-8 rounded-[1.5rem] flex items-center shadow-sm text-xs font-black uppercase tracking-widest">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="reveal-section bg-white shadow-sm rounded-[3.5rem] overflow-hidden border border-slate-100 mb-10">
        <div class="overflow-x-auto no-scrollbar">
            <table class="min-w-full divide-y divide-slate-50 table-fixed">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="w-72 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Voucher Identity</th>
                        <th class="w-48 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Benefit Value</th>
                        <th class="w-40 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Usage Limit</th>
                        <th class="w-40 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="w-40 px-10 py-7 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($vouchers as $v)
                        <tr x-show="!deletedVouchers.includes({{ $v->id }})"
                            x-transition:leave="transition ease-in duration-400 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 -translate-x-12"
                            class="hover:bg-slate-50/30 transition-all group">
                            
                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-slate-900 text-white px-4 py-2 rounded-xl font-black text-xs tracking-widest group-hover:bg-sky-500 transition-colors">
                                        {{ $v->code }}
                                    </div>
                                    <div class="ml-5">
                                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Expiration</div>
                                        <div class="text-xs font-black text-slate-900 tracking-tight">
                                            {{ $v->expires_at ? $v->expires_at->format('d M, Y') : 'Life-Time' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="text-base font-black text-slate-900 tracking-tighter">
                                    @if($v->type === 'percent') {{ number_format($v->value, 0) }}% OFF @else Rp{{ number_format($v->value, 0, ',', '.') }} @endif
                                </div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Min: Rp{{ number_format($v->min_spend, 0, ',', '.') }}</div>
                            </td>

                            <td class="px-10 py-6 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-xs font-black text-slate-700 mb-1.5">{{ $v->used_count }} / {{ $v->max_uses ?? '∞' }} Used</div>
                                    <div class="w-24 bg-slate-100 h-1 rounded-full overflow-hidden text-left" 
                                         style="--usage-width: {{ $v->max_uses ? ($v->used_count / $v->max_uses) * 100 : 0 }}%">
                                        <div class="bg-sky-500 h-full transition-all duration-1000" style="width: var(--usage-width)"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-10 py-6 whitespace-nowrap text-center">
                                @php $isExpired = $v->expires_at && $v->expires_at->isPast(); @endphp
                                <span class="inline-flex px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border
                                    {{ ($v->is_active && !$isExpired) ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-500 border-rose-100' }}">
                                    {{ $isExpired ? 'Expired' : ($v->is_active ? 'Active' : 'Inactive') }}
                                </span>
                            </td>

                            <td class="px-10 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="{{ route('admin.vouchers.edit', $v) }}" 
                                       class="px-4 py-2 bg-slate-50 text-slate-400 hover:text-sky-500 hover:bg-white hover:shadow-md rounded-xl text-[9px] font-black uppercase tracking-widest border border-slate-100 transition-all">
                                        Edit
                                    </a>
                                    
                                    <button type="button" @click="confirmTerminate({{ $v->id }}, '{{ $v->code }}')"
                                            class="p-2.5 bg-rose-50 text-rose-400 hover:text-white hover:bg-rose-500 rounded-xl transition-all border border-rose-100 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                    
                                    <form id="delete-form-{{ $v->id }}" action="{{ route('admin.vouchers.destroy', $v) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-10 py-32 text-center text-slate-300 font-black uppercase tracking-widest text-xs">Zero vouchers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="reveal-section flex justify-center">{{ $vouchers->links() }}</div>
</div>

<script>
function voucherManager() {
    return {
        deletedVouchers: [],
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },
        confirmTerminate(id, code) {
            Swal.fire({
                title: 'Terminate Item?',
                text: `Hapus voucher "${code}" secara permanen?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletedVouchers.push(id);
                    setTimeout(() => {
                        document.getElementById('delete-form-' + id).submit();
                    }, 400);
                }
            });
        }
    }
}
</script>
@endsection