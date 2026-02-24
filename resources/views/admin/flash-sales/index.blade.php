@extends('layouts.app')

@section('title', 'Flash Sale Management - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
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
        
        // Pengecekan status aktif
        if (product.flash_sale_price) {
            this.isEditing = true;
            this.flashSalePrice = product.flash_sale_price;
            // Format tanggal untuk input datetime-local (YYYY-MM-DDTHH:mm)
            this.flashSaleStart = product.flash_sale_start ? product.flash_sale_start.replace(' ', 'T').slice(0, 16) : '';
            this.flashSaleEnd = product.flash_sale_end ? product.flash_sale_end.replace(' ', 'T').slice(0, 16) : '';
        } else {
            this.isEditing = false;
            this.flashSalePrice = '';
            this.flashSaleStart = '';
            this.flashSaleEnd = '';
        }
        
        this.showModal = true;
    },
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
    }
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    {{-- Header Section --}}
    <div class="mb-10 text-left">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Flash Sale <span class="text-sky-500">Management</span></h1>
        <p class="text-slate-500 mt-2 font-medium">Atur harga promo kilat dan durasi waktu untuk komponen Pitocom.</p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-400 text-rose-700 p-4 mb-8 rounded-2xl shadow-sm" role="alert">
            <h3 class="font-bold text-lg">Data Submission Failed</h3>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-2xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Table Section --}}
    <div class="bg-white shadow-sm rounded-[2.5rem] overflow-x-auto border border-slate-100">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Product Info</th>
                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Sale Price</th>
                    <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Duration</th>
                    <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6 whitespace-nowrap text-left">
                            <div class="text-sm font-bold text-slate-900">{{ $product->name }}</div>
                            <div class="text-xs text-slate-400 font-medium mt-0.5">Base: Rp {{ number_format($product->base_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-left">
                            @if ($product->is_flash_sale_active)
                                <div class="flex items-center">
                                    <span class="px-3 py-1 text-[10px] font-black rounded-full bg-emerald-100 text-emerald-600 uppercase tracking-widest">Active</span>
                                </div>
                                <div class="text-xs text-slate-400 mt-1">Ends in: {{ $product->flash_sale_end->diffForHumans() }}</div>
                            @else
                                @if (!$product->flash_sale_start && !$product->flash_sale_price)
                                     <span class="px-3 py-1 text-[10px] font-black rounded-full bg-slate-100 text-slate-400 uppercase tracking-widest">Inactive</span>
                                @elseif (now()->lt($product->flash_sale_start))
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-[10px] font-black rounded-full bg-yellow-100 text-yellow-800 uppercase tracking-widest">Scheduled</span>
                                    </div>
                                    <div class="text-xs text-slate-400 mt-1">Starts in: {{ $product->flash_sale_start->diffForHumans() }}</div>
                                @else
                                     <div class="flex items-center">
                                        <span class="px-3 py-1 text-[10px] font-black rounded-full bg-rose-100 text-rose-500 uppercase tracking-widest">Expired</span>
                                    </div>
                                    @if($product->flash_sale_end)
                                    <div class="text-xs text-slate-400 mt-1">Ended: {{ $product->flash_sale_end->diffForHumans() }}</div>
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm font-bold text-sky-600 text-left">
                            {{ $product->flash_sale_price ? 'Rp ' . number_format($product->flash_sale_price, 0, ',', '.') : '—' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-500 font-medium text-left text-xs">
                             {{ $product->flash_sale_start ? $product->flash_sale_start->format('d M, H:i') : '' }}
                             {{ $product->flash_sale_start ? '→' : '—' }}
                             {{ $product->flash_sale_end ? $product->flash_sale_end->format('d M, H:i') : '' }}
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="openModal({{ json_encode($product) }})" 
                                        class="text-xs font-black text-sky-500 hover:text-sky-700 uppercase tracking-widest transition-colors">
                                    Manage
                                </button>
                                @if($product->flash_sale_price)
                                    <form action="{{ route('admin.flash-sales.destroy', $product) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors" 
                                                onclick="return confirm('Hapus produk dari daftar Flash Sale?')">
                                            Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-medium italic">Data produk tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

    <div x-show="showModal" 
         class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4" 
         style="display: none;">
        
        {{-- Overlay dengan Backdrop Blur --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

        {{-- Box Modal --}}
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 z-[110]">
            
            <form :action="actionUrl" method="POST" class="p-10">
                @csrf
                <div class="mb-8 text-left">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Flash Sale Setup</h3>
                    <p class="text-sm text-slate-500 mt-1">Konfigurasi harga promo untuk <span x-text="productName" class="text-sky-500 font-bold"></span></p>
                </div>

                <div class="space-y-5 text-left">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Sale Price (IDR)</label>
                        <input type="number" name="flash_sale_price" x-model="flashSalePrice" required 
                               class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all"
                               placeholder="Masukkan harga diskon">
                        <p class="text-[10px] text-slate-400 mt-2 ml-1 italic">Harga dasar: <span x-text="formatPrice(basePrice)"></span></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Start Time</label>
                            <input type="datetime-local" name="flash_sale_start" x-model="flashSaleStart" required
                                   class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium text-slate-600 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">End Time</label>
                            <input type="datetime-local" name="flash_sale_end" x-model="flashSaleEnd" required
                                   class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium text-slate-600 text-xs">
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-2xl font-black shadow-lg shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95">
                        Save Changes
                    </button>
                    <button type="button" @click="showModal = false" class="w-full sm:w-auto px-8 py-4 bg-slate-50 text-slate-400 rounded-2xl font-bold hover:bg-slate-100 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection