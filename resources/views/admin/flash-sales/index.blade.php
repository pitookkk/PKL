@extends('layouts.app')

@section('title', 'Flash Sale Hub - Pitocom Admin')

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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
    
<div x-data="{
    showModal: false,
    isEditing: false,
    actionUrl: '',
    productName: '',
    basePrice: 0,
    flashSalePrice: '',
    flashSaleStart: '',
    flashSaleEnd: '',

    openModal(product) {
        this.actionUrl = '{{ url('admin/flash-sales') }}/' + product.id;
        this.productName = product.name;
        this.basePrice = product.base_price;
        
        if (product.flash_sale_price) {
            this.isEditing = true;
            this.flashSalePrice = product.flash_sale_price;
            this.flashSaleStart = product.flash_sale_start ? product.flash_sale_start.replace(' ', 'T').slice(0, 16) : '';
            this.flashSaleEnd = product.flash_sale_end ? product.flash_sale_end.replace(' ', 'T').slice(0, 16) : '';
        } else {
            this.isEditing = false;
            this.flashSalePrice = '';
            this.flashSaleStart = '';
            this.flashSaleEnd = '';
        }
        
        this.showModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.showModal = false;
        document.body.style.overflow = 'auto';
    },
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
    },
    initObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
    }
}" x-init="initObserver()" class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Flash Sale <span class="text-sky-500">Hub.</span></h1>
        <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1">Hardware Promo Management System</p>
        <div class="h-1 w-20 bg-sky-500 mt-5 rounded-full text-left"></div>
    </div>

    {{-- Messages --}}
    <div class="reveal-section">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-5 mb-8 rounded-[1.5rem] flex items-center shadow-sm text-xs font-black uppercase tracking-widest text-left">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- Table Section - Optimized Layout --}}
    <div class="reveal-section bg-white shadow-sm rounded-[3rem] overflow-hidden border border-slate-100 mb-10">
        <div class="overflow-x-auto no-scrollbar text-left">
            <table class="min-w-full divide-y divide-slate-50 table-fixed text-left">
                <thead class="bg-slate-50/50 text-left">
                    <tr>
                        <th class="w-20 px-6 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Visual</th>
                        <th class="w-64 px-6 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Hardware Identity</th>
                        <th class="w-32 px-6 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="w-40 px-6 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Sale Price</th>
                        <th class="w-56 px-6 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Window</th>
                        <th class="w-44 px-6 py-7 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/30 transition-all group">
                            {{-- Visual Image --}}
                            <td class="px-6 py-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 shadow-inner group-hover:rotate-3 transition-transform duration-500">
                                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/80' }}" class="w-full h-full object-cover">
                                </div>
                            </td>

                            {{-- Identity --}}
                            <td class="px-6 py-4">
                                <div class="text-xs font-black text-slate-900 truncate tracking-tight mb-0.5" title="{{ $product->name }}">{{ $product->name }}</div>
                                <div class="text-[8px] text-slate-400 font-black uppercase tracking-tighter italic">Base: Rp{{ number_format($product->base_price, 0, ',', '.') }}</div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if ($product->is_flash_sale_active)
                                    <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest border border-emerald-100">Live</span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full bg-slate-50 text-slate-300 text-[8px] font-black uppercase tracking-widest border border-slate-100">OFF</span>
                                @endif
                            </td>

                            {{-- Price --}}
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-black text-sky-600 tracking-tighter leading-none">{{ $product->flash_sale_price ? 'Rp' . number_format($product->flash_sale_price, 0, ',', '.') : '—' }}</p>
                            </td>

                            {{-- Window --}}
                            <td class="px-6 py-4">
                                @if($product->flash_sale_start)
                                    <div class="text-slate-500 text-[9px] font-black uppercase tracking-tighter">
                                        {{ $product->flash_sale_start->format('d M') }} <span class="mx-1 text-slate-300">→</span> {{ $product->flash_sale_end->format('d M, H:i') }}
                                    </div>
                                @else
                                    <span class="text-slate-200 text-[9px] font-black italic">No Schedule</span>
                                @endif
                            </td>

                            {{-- Actions (Fixed Visibility) --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end items-center space-x-3">
                                    <button type="button" @click="openModal({{ json_encode($product) }})" 
                                            class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-[0.1em] hover:bg-sky-500 transition-all shadow-lg active:scale-90">
                                        Manage
                                    </button>
                                    
                                    @if($product->flash_sale_price)
                                        <form action="{{ route('admin.flash-sales.destroy', $product) }}" method="POST" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-400 hover:text-white hover:bg-rose-500 rounded-xl transition-all border border-rose-100" 
                                                    onclick="return confirm('Kill promo for this hardware?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-10 py-32 text-center text-slate-300 font-black uppercase tracking-widest text-xs">No active flash sales data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="reveal-section flex justify-center">{{ $products->links() }}</div>

    {{-- MODAL SETUP --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-6 bg-slate-950/95 backdrop-blur-xl" @keydown.escape.window="closeModal()">
        <div class="bg-white rounded-[3.5rem] shadow-2xl w-full max-w-lg relative overflow-hidden animate-fade-in-up" @click.away="closeModal()">
            <button @click="closeModal()" class="absolute top-8 right-8 z-20 w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:rotate-90 transition-all focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <form :action="actionUrl" method="POST" class="p-12 text-left">
                @csrf
                <div class="mb-12">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">Promo <span class="text-sky-500">Config.</span></h3>
                    <p class="text-slate-400 text-[11px] font-black uppercase tracking-widest mt-3 truncate" x-text="productName"></p>
                    <div class="h-1 w-12 bg-sky-500 mt-5 rounded-full text-left"></div>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 text-left">Flash Price (IDR)</label>
                        <input type="number" name="flash_sale_price" x-model="flashSalePrice" required class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 shadow-inner text-xl transition-all">
                        <p class="text-[9px] text-slate-300 font-black uppercase tracking-tighter mt-3 ml-1">Standard MSRP: <span x-text="formatPrice(basePrice)" class="text-slate-500"></span></p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 text-left">Start Date</label>
                            <input type="datetime-local" name="flash_sale_start" x-model="flashSaleStart" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-1 text-left">Expiration</label>
                            <input type="datetime-local" name="flash_sale_end" x-model="flashSaleEnd" required class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner text-xs">
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex flex-col sm:flex-row-reverse gap-4">
                    <button type="submit" class="w-full sm:w-auto px-12 py-5 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95 text-xs">Update Setup</button>
                    <button type="button" @click="closeModal()" class="w-full sm:w-auto px-10 py-5 bg-slate-100 text-slate-400 rounded-[1.8rem] font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-xs">Discard</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection