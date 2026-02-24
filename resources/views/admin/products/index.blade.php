@extends('layouts.app')

@section('title', 'Inventory Management - Pitocom Admin')

@section('content')
    {{-- Load Font & SweetAlert2 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .swal2-popup { border-radius: 2.5rem !important; padding: 2rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
        .swal2-styled.swal2-cancel { border-radius: 1rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-4 py-12" x-data="productManagement()">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 text-left animate-fade-in-up">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Manage <span class="text-sky-500">Products</span></h1>
                <p class="text-slate-500 mt-2 font-medium text-lg">Pantau stok, harga, dan katalog hardware Pitocom secara real-time.</p>
                <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
            </div>
            
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center justify-center bg-slate-900 text-white px-10 py-4 rounded-[2rem] font-black uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95 text-xs group">
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Visual</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Product Details</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Category</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Base Price</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Stock Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Featured</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($products as $product)
                            {{-- Exit Animation Alpine.js --}}
                            <tr x-show="!deletedProducts.includes({{ $product->id }})" 
                                x-transition:leave="transition ease-in duration-300 transform"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95 -translate-x-4"
                                class="hover:bg-slate-50/30 transition-colors group">
                                
                                {{-- Image --}}
                                <td class="px-8 py-5">
                                    <div class="relative w-16 h-16 overflow-hidden rounded-2xl shadow-inner bg-slate-50 border border-slate-100">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/80' }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    </div>
                                </td>

                                {{-- Info --}}
                                <td class="px-8 py-5 text-left">
                                    <p class="font-black text-slate-900 text-base leading-tight tracking-tight">{{ $product->name }}</p>
                                    <p class="text-[10px] text-sky-500 font-black uppercase tracking-widest mt-1">{{ $product->brand ?? 'PITOCOM BUILD' }}</p>
                                </td>

                                {{-- Category --}}
                                <td class="px-8 py-5 text-left">
                                    <span class="inline-block px-4 py-1.5 bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-200 shadow-sm">
                                        {{ $product->category->name }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td class="px-8 py-5 text-left">
                                    <p class="font-black text-slate-900 tracking-tighter text-lg">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                </td>

                                {{-- Stock Status --}}
                                <td class="px-8 py-5 text-left">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 rounded-full mr-2 {{ $product->stock < 5 ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                                        <span class="font-black {{ $product->stock < 5 ? 'text-rose-600' : 'text-slate-700' }} text-sm tracking-tight">
                                            {{ $product->stock }} <span class="text-[10px] text-slate-400 uppercase tracking-tighter ml-1">Units</span>
                                        </span>
                                    </div>
                                </td>

                                {{-- Featured --}}
                                <td class="px-8 py-5 text-center">
                                    @if($product->is_featured)
                                        <div class="inline-flex items-center justify-center p-2 bg-amber-50 rounded-xl border border-amber-100 shadow-sm">
                                            <svg class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                                        </div>
                                    @else
                                        <span class="inline-block w-2 h-2 bg-slate-200 rounded-full"></span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="p-2.5 text-slate-400 hover:text-sky-500 hover:bg-sky-50 rounded-xl transition-all shadow-sm bg-white border border-slate-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        
                                        {{-- Delete Button with Custom Function --}}
                                        <button type="button" @click="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="p-2.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all shadow-sm bg-white border border-slate-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>

                                        {{-- Hidden Delete Form --}}
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-32 text-center">
                                    <div class="bg-slate-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-slate-400 font-black uppercase tracking-widest">Inventory is Empty</p>
                                    <p class="text-slate-300 text-sm mt-1 italic font-medium">Belum ada produk yang terdaftar di sistem.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-8 py-8 bg-slate-50/50 border-t border-slate-50">
                {{ $products->links() }}
            </div>
        </div>
    </div>

<script>
function productManagement() {
    return {
        deletedProducts: [],
        
        confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Hapus Hardware?',
                text: `Produk "${productName}" akan dihapus permanen dari katalog Pitocom.`,
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
                    // Trigger Alpine.js Exit Animation
                    this.deletedProducts.push(productId);
                    
                    // Delay for animation duration before form submission
                    setTimeout(() => {
                        document.getElementById(`delete-form-${productId}`).submit();
                    }, 400);
                }
            })
        }
    }
}
</script>
@endsection