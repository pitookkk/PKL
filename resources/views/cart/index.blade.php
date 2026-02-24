@extends('layouts.app')

@section('title', 'Shopping Cart - Pitocom')

@section('content')
{{-- Load Font Premium & SweetAlert2 --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style> 
    body { font-family: 'Plus Jakarta Sans', sans-serif; } 
    [x-cloak] { display: none !important; }
    .swal2-popup { border-radius: 2.5rem !important; padding: 2rem !important; }
    .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
    .swal2-styled.swal2-cancel { border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
</style>

<div class="max-w-7xl mx-auto px-4 py-12" x-data="cartHandler()">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 text-left animate-fade-in-up">
        <div class="flex items-center space-x-5">
            <div class="bg-slate-900 p-4 rounded-[1.5rem] shadow-xl text-white group-hover:rotate-6 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tight">Your <span class="text-sky-500">Cart</span></h1>
                <p class="text-slate-500 mt-2 font-medium text-lg italic">Kelola belanjaanmu sebelum melanjutkan ke rakitan pembayaran.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-none p-5 mb-10 rounded-3xl text-left flex items-center shadow-sm">
            <div class="bg-emerald-500 text-white p-1 rounded-full mr-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="font-black text-emerald-700 uppercase tracking-widest text-xs">{{ session('success') }}</span>
        </div>
    @endif

    @if(!empty($cart))
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        {{-- Kolom Kiri: Daftar Barang --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="divide-y divide-slate-50">
                    @foreach($cart as $id => $details)
                    {{-- Animasi Exit dengan x-show dan x-transition --}}
                    <div x-show="!deletedItems.includes('{{ $id }}')"
                         x-transition:leave="transition ease-in duration-300 transform"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95 -translate-x-4"
                         class="p-10 flex flex-col md:flex-row items-center gap-10 text-left group transition-all hover:bg-slate-50/50">
                        
                        {{-- Image --}}
                        <div class="w-40 h-40 flex-shrink-0 bg-slate-50 rounded-[2rem] overflow-hidden border-2 border-white shadow-md relative group-hover:shadow-xl transition-all">
                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://via.placeholder.com/150' }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $details['name'] }}</h3>
                                    @if($details['variation_name'])
                                        <span class="inline-flex mt-2 px-4 py-1.5 bg-sky-50 text-sky-600 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border border-sky-100">
                                            {{ $details['variation_name'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-2xl font-black text-slate-900 tracking-tighter">
                                    Rp {{ number_format($details['price'], 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between mt-10">
                                <div class="flex items-center bg-slate-50 p-1.5 rounded-[1.5rem] border border-slate-100 shadow-inner">
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
                                        <button type="submit" class="w-11 h-11 flex items-center justify-center bg-white rounded-2xl shadow-sm text-slate-400 hover:text-sky-500 transition-all" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                        </button>
                                    </form>
                                    <span class="w-14 text-center font-black text-slate-900 text-xl">{{ $details['quantity'] }}</span>
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                        <button type="submit" class="w-11 h-11 flex items-center justify-center bg-white rounded-2xl shadow-sm text-slate-400 hover:text-sky-500 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- Trigger tombol delete dengan Animasi --}}
                                <button type="button" @click="confirmDelete('{{ $id }}', '{{ addslashes($details['name']) }}')"
                                        class="flex items-center space-x-2 text-rose-300 hover:text-rose-500 font-black text-[10px] transition-all uppercase tracking-[0.2em] group/remove">
                                    <div class="p-2.5 rounded-xl bg-rose-50 group-hover/remove:bg-rose-500 group-hover/remove:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </div>
                                    <span class="hidden md:inline">Remove Item</span>
                                </button>

                                {{-- Hidden form asli --}}
                                <form id="delete-form-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-10" x-show="!showAddressForm">
            {{-- Promo Code Section --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm text-left relative overflow-hidden group">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="bg-indigo-50 text-indigo-500 p-3 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Promo Code</h4>
                </div>

                @if(session('applied_voucher'))
                    <div class="bg-indigo-50 p-4 rounded-2xl border border-indigo-100 flex justify-between items-center mb-2">
                        <div>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none mb-1">Applied Code</p>
                            <p class="font-black text-indigo-600">{{ session('applied_voucher.code') }}</p>
                        </div>
                        <form action="{{ route('vouchers.remove') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-rose-400 hover:text-rose-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-tight ml-1 italic">✓ Diskon Rp {{ number_format(session('applied_voucher.discount_amount'), 0, ',', '.') }} berhasil dipasang!</p>
                @else
                    <form action="{{ route('vouchers.apply') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="ENTER CODE" class="flex-1 bg-slate-50 border-none rounded-2xl px-5 py-3 text-xs font-black tracking-widest focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-black text-[10px] hover:bg-indigo-600 transition-all uppercase tracking-widest">Apply</button>
                    </form>
                    @if(session('error'))
                        <p class="text-[10px] font-bold text-rose-500 mt-2 ml-1 uppercase tracking-tight">{{ session('error') }}</p>
                    @endif
                @endif
            </div>

            {{-- Order Summary --}}
            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-white shadow-2xl text-left overflow-hidden border-4 border-slate-800 relative">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl animate-pulse"></div>
                <h3 class="text-xs font-black mb-10 tracking-[0.3em] uppercase text-slate-500">Order Summary</h3>
                <div class="space-y-6 mb-10 relative z-10 text-left">
                    <div class="flex justify-between text-slate-400 font-bold text-xs uppercase tracking-widest">
                        <span>Items Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    @if(session('applied_voucher'))
                        <div class="flex justify-between text-emerald-400 font-bold text-xs uppercase tracking-widest">
                            <span>Promo Discount</span>
                            <span>- Rp {{ number_format(session('applied_voucher.discount_amount'), 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-400 font-bold text-xs uppercase tracking-widest">
                        <span>Shipping (Flat)</span>
                        <span>Rp 20.000</span>
                    </div>
                    <div class="h-px bg-slate-800 my-8"></div>
                    <div class="flex flex-col text-left">
                        <span class="text-[10px] font-black text-sky-500 uppercase tracking-[0.3em] mb-1">Total Bill</span>
                        @php
                            $discount = session('applied_voucher.discount_amount', 0);
                            $grandTotal = ($total - $discount) + 20000;
                        @endphp
                        <span class="text-4xl font-black text-white tracking-tighter leading-none text-left">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button @click="showAddressForm = true" class="w-full bg-sky-500 hover:bg-white hover:text-slate-900 text-white font-black py-5 rounded-3xl transition-all shadow-xl active:scale-95 flex items-center justify-center space-x-3 uppercase tracking-widest text-xs text-left">
                    <span>Checkout Now</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
            
            {{-- Shipping Info --}}
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm text-left relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-500/5 rounded-full transition-transform duration-700 group-hover:scale-150"></div>
                <div class="flex items-center space-x-4 mb-6 relative">
                    <div class="bg-sky-100 text-sky-500 p-3 rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest text-left">Shipping Origin</h4>
                        <p class="text-[10px] text-sky-500 font-black uppercase mt-0.5 tracking-tighter text-left">Pitocom Official Store</p>
                    </div>
                </div>
                <p class="text-sm text-slate-500 leading-relaxed font-bold italic border-l-4 border-slate-100 pl-6 mb-8 text-left">
                    Jl. Komponen Gaming No. 123, Kec. Rakitan, Kota Jakarta Selatan, DKI Jakarta.
                </p>
            </div>
        </div>

        {{-- Form Alamat --}}
        <div class="lg:col-span-1" x-show="showAddressForm" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-12">
            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8 text-left">
                @csrf
                <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100 p-10 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8">
                        <div class="w-12 h-12 rounded-2xl bg-slate-900 flex items-center justify-center text-white font-black text-lg text-left">1</div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-10 tracking-tight uppercase text-left">Delivery <span class="text-sky-500">Info</span></h3>
                    <div class="space-y-8 text-left">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Recipient Name</label>
                            <input type="text" name="recipient_name" required value="{{ auth()->user()->name }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-slate-700 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">WhatsApp Number</label>
                            <input type="text" name="phone_number" required value="{{ auth()->user()->phone_number }}" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-slate-700 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Full Address</label>
                            <textarea name="address" rows="4" required class="w-full bg-slate-50 border-none rounded-[2rem] px-6 py-4 font-bold text-slate-700 shadow-inner leading-relaxed"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-6 text-left">
                            <input type="text" name="city_name" placeholder="City" required class="bg-slate-50 border-none rounded-2xl px-6 py-4 font-bold text-slate-700 shadow-inner">
                            <select name="courier" class="bg-slate-50 border-none rounded-2xl px-6 py-4 font-black text-slate-700 shadow-inner">
                                <option value="Standard">Standard</option>
                                <option value="Instant">Instant</option>
                            </select>
                        </div>
                        <input type="hidden" name="shipping_cost" value="20000">
                    </div>
                    <div class="h-px bg-slate-100 my-10 text-left"></div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2rem] font-black text-xl shadow-xl hover:bg-sky-500 transition-all active:scale-95 uppercase text-left">Confirm & Pay</button>
                    <button type="button" @click="showAddressForm = false" class="w-full py-4 text-slate-400 font-black text-[10px] uppercase hover:text-slate-900 text-left">&larr; Back</button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="text-center bg-white p-24 rounded-[4rem] shadow-sm border border-slate-100 max-w-3xl mx-auto">
        <h2 class="text-4xl font-black text-slate-900 mb-4 text-left">Empty Cart</h2>
        <a href="{{ route('products.index') }}" class="bg-slate-900 text-white px-12 py-5 rounded-[2rem] font-black text-left">Mulai Belanja</a>
    </div>
    @endif
</div>

<script>
function cartHandler() {
    return {
        showAddressForm: false,
        deletedItems: [],
        confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Barang?',
                text: `Apakah Anda yakin ingin menghapus "${name}" dari keranjang?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                customClass: {
                    title: 'font-black text-slate-900 uppercase tracking-tight',
                    htmlContainer: 'font-bold text-slate-500',
                    cancelButton: 'text-slate-400 font-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deletedItems.push(id);
                    setTimeout(() => {
                        document.getElementById(`delete-form-${id}`).submit();
                    }, 400);
                }
            })
        }
    }
}
</script>
@endsection