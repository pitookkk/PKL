@extends('layouts.app')

@section('title', 'My Dashboard - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    {{-- Header Dashboard --}}
    <div class="mb-10 animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight text-left">Customer Dashboard</h1>
        <p class="text-slate-500 font-medium text-left">Selamat datang kembali, kelola profil dan pantau pesananmu di sini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        {{-- User Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 sticky top-28 text-left">
                <div class="w-20 h-20 bg-sky-500 text-white rounded-3xl flex items-center justify-center text-3xl font-black mb-6 shadow-lg shadow-sky-100">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-2xl font-black text-slate-900 mb-6 tracking-tight">My Profile</h2>
                
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Full Name</p>
                        <p class="font-bold text-slate-800">{{ $user->name }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Email Address</p>
                        <p class="font-bold text-slate-800 lowercase">{{ $user->email }}</p>
                    </div>
                </div>

                <button class="w-full mt-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-sky-500 transition-all active:scale-95 shadow-xl shadow-slate-200">
                    Edit Profile
                </button>
            </div>
        </div>
        
        {{-- Order History Section --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 text-left">
                <h2 class="text-2xl font-black text-slate-900 mb-8 tracking-tight flex items-center">
                    <svg class="w-6 h-6 mr-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Order History
                </h2>

                <div class="space-y-6">
                    @forelse ($orders as $order)
                        <div class="bg-slate-50/50 rounded-[2rem] p-6 border border-slate-100 transition-all hover:bg-white hover:shadow-md group" x-data="{ open: false }">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm font-black text-slate-400 border border-slate-100">
                                        #{{ $order->id }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800 leading-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        <p class="text-xs text-slate-400 font-bold tracking-wide uppercase mt-1">{{ $order->order_date->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4 w-full md:w-auto justify-between">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $currentStyle = $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full border {{ $currentStyle }} text-[10px] font-black uppercase tracking-widest">
                                        {{ $order->status }}
                                    </span>
                                    <button @click="open = !open" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-sky-500 transition-all border border-slate-100">
                                        <svg class="w-5 h-5 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Collapsible Items --}}
                            <div x-show="open" x-collapse class="mt-6 pt-6 border-t border-slate-200/50 space-y-4">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Items Purchased</h4>
                                @foreach($order->items as $item)
                                    <div class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm">
                                        <div class="flex items-center space-x-3">
                                            <span class="w-8 h-8 bg-sky-50 text-sky-500 rounded-lg flex items-center justify-center text-xs font-bold">{{ $item->quantity }}x</span>
                                            <p class="font-bold text-slate-700 text-sm">{{ $item->product->name }}</p>
                                        </div>
                                        
                                        @if($order->status == 'completed')
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('orders.invoice', $order) }}" 
                                                   class="text-[10px] font-black border-2 border-slate-900 text-slate-900 px-4 py-2 rounded-xl hover:bg-slate-900 hover:text-white transition-all active:scale-90 flex items-center">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    DOWNLOAD INVOICE
                                                </a>

                                                @if($reviewedProductIds->contains($item->product_id))
                                                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-lg">Reviewed</span>
                                                @else
                                                    <button @click="$dispatch('open-review-modal', { productId: {{ $item->product_id }}, orderId: {{ $order->id }} })" 
                                                            class="text-[10px] font-black bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-sky-500 transition-all active:scale-90">
                                                        WRITE REVIEW
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <div class="text-6xl mb-4 text-slate-200 italic">📦</div>
                            <p class="text-slate-400 font-bold text-lg">No orders found.</p>
                            <p class="text-slate-300 text-sm mt-1 uppercase tracking-widest font-bold">Mulai bangun PC impianmu sekarang</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-10">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Review Modal --}}
<div x-data="{ open: false, productId: null, orderId: null }" 
     @open-review-modal.window="open = true; productId = $event.detail.productId; orderId = $event.detail.orderId"
     class="z-[60]">
    <div x-show="open" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;"></div>
    
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-8" 
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed inset-0 z-[70] overflow-y-auto flex items-center justify-center p-4" style="display: none;">
        
        <div @click.away="open = false" class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl p-10 relative overflow-hidden text-left">
            {{-- Decoration --}}
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl"></div>

            <button @click="open = false" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Share Your Thoughts</h3>
            <p class="text-slate-400 font-medium mb-8 text-sm uppercase tracking-widest">Berikan rating terbaikmu untuk Pitocom.</p>

            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" x-data="reviewForm()">
                @csrf
                <input type="hidden" name="product_id" :value="productId">
                <input type="hidden" name="order_id" :value="orderId">
                
                <div class="mb-8">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">How was your experience?</label>
                    <div class="flex items-center space-x-2" @mouseleave="hoverRating = 0">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" @mouseenter="hoverRating = star" @click="rating = star" class="transition-transform active:scale-75">
                                <svg class="w-10 h-10" :class="(hoverRating >= star || rating >= star) ? 'text-amber-400 fill-current' : 'text-slate-100 fill-current'" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        </template>
                        <span class="ml-4 text-xl font-black text-slate-300" x-text="rating + '/5'"></span>
                    </div>
                    <input type="hidden" name="rating" x-model="rating">
                </div>

                <div class="mb-8">
                    <label for="comment" class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Your Feedback</label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Kualitas barang sangat oke..."
                              class="w-full px-6 py-4 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-medium transition-all"></textarea>
                </div>

                <button type="submit" class="w-full py-5 bg-sky-500 text-white rounded-3xl font-black text-lg hover:bg-sky-400 transition-all shadow-xl shadow-sky-100 active:scale-95 disabled:opacity-50" :disabled="rating === 0">
                    Kirim Ulasan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs-collapse@1.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reviewForm', () => ({
            rating: 0,
            hoverRating: 0,
        }));
    });
</script>
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush