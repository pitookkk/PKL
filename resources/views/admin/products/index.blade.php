@extends('layouts.app')

@section('title', 'Inventory Management - Pitocom Admin')

@section('content')
    {{-- Load Font & SweetAlert2 --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .no-scrollbar::-webkit-scrollbar { display: none; }

        .swal2-popup { border-radius: 2.5rem !important; padding: 2.5rem !important; }
        .swal2-styled.swal2-confirm { background-color: #0f172a !important; border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
        .swal2-styled.swal2-cancel { border-radius: 1.2rem !important; padding: 1rem 2.5rem !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; font-size: 11px !important; }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

    <div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
         x-data="productManagement()" 
         x-init="initObserver()">
        
        {{-- Header Section --}}
        <div class="reveal-section flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 text-left">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Inventory <span class="text-sky-500">Hub.</span></h1>
                <p class="text-slate-500 mt-2 font-medium text-lg italic pl-1 text-left">Monitoring hardware Pitocom secara real-time.</p>
                <div class="h-1.5 w-24 bg-sky-500 mt-5 rounded-full text-left"></div>
            </div>
            
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center justify-center bg-slate-900 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest shadow-2xl shadow-slate-200 hover:bg-sky-50 transition-all hover:text-sky-600 active:scale-95 text-xs group shrink-0">
                <svg class="w-4 h-4 mr-3 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Add Hardware
            </a>
        </div>

        {{-- Table Card --}}
        <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20" style="transition-delay: 200ms">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left table-fixed"> {{-- Menggunakan table-fixed agar kolom terkunci --}}
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="w-24 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Img</th>
                            <th class="w-64 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Identity</th>
                            <th class="w-32 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center text-left">Category</th>
                            <th class="w-32 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Price</th>
                            <th class="w-32 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-left">Stock</th>
                            <th class="w-24 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center text-left">Stars</th>
                            <th class="w-32 px-8 py-6 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right text-left">Ops</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($products as $index => $product)
                            <tr x-show="!deletedProducts.includes({{ $product->id }})" 
                                x-transition:leave="transition ease-in duration-300 transform"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95 -translate-x-8"
                                class="hover:bg-slate-50/30 transition-all group"
                                style="--row-delay: {{ $index * 50 }}ms; transition-delay: var(--row-delay);">
                                
                                {{-- Visual --}}
                                <td class="px-8 py-5 text-left">
                                    <div class="relative w-14 h-14 overflow-hidden rounded-xl shadow-inner bg-slate-50 border border-slate-100 text-left text-left">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/80' }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    </div>
                                </td>

                                {{-- Info --}}
                                <td class="px-8 py-5 text-left">
                                    <p class="font-black text-slate-900 text-sm truncate leading-none mb-1 text-left" title="{{ $product->name }}">{{ $product->name }}</p>
                                    <p class="text-[9px] text-sky-500 font-black uppercase tracking-tighter text-left">{{ $product->brand ?? 'PITOCOM BUILD' }}</p>
                                </td>

                                {{-- Category --}}
                                <td class="px-8 py-5 text-center text-left">
                                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 text-[8px] font-black uppercase tracking-tighter rounded-full border border-slate-200">
                                        {{ $product->category->name }}
                                    </span>
                                </td>

                                {{-- Price --}}
                                <td class="px-8 py-5 text-left">
                                    <p class="font-black text-slate-900 tracking-tighter text-sm leading-none text-left text-left">Rp{{ number_format($product->base_price, 0, ',', '.') }}</p>
                                </td>

                                {{-- Stock --}}
                                <td class="px-8 py-5 text-left">
                                    <div class="flex flex-col text-left">
                                        <div class="flex items-center mb-1 text-left">
                                            <div class="w-1.5 h-1.5 rounded-full mr-2 {{ $product->stock < 5 ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                                            <span class="font-black text-slate-700 text-xs tracking-tight text-left">
                                                {{ $product->stock }} <span class="text-[8px] text-slate-400 uppercase tracking-tighter ml-0.5">Unit</span>
                                            </span>
                                        </div>
                                        <div class="w-16 bg-slate-100 h-0.5 rounded-full overflow-hidden text-left">
                                            <div class="h-full {{ $product->stock < 5 ? 'bg-rose-500' : 'bg-emerald-500' }}" 
                                                 style="--stock-percent: {{ min(($product->stock / 20) * 100, 100) }}%; width: var(--stock-percent);"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-8 py-5 text-center text-left">
                                    @if($product->is_featured)
                                        <svg class="w-4 h-4 text-amber-400 mx-auto fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.959a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.446a1 1 0 00-.364 1.118l1.287 3.959c.3.921-.755 1.688-1.54 1.118l-3.368-2.446a1 1 0 00-1.175 0l-3.368 2.446c-.784.57-1.838-.197-1.539-1.118l1.287-3.959a1 1 0 00-.364-1.118L2.05 9.386c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                                    @else
                                        <span class="inline-block w-1.5 h-1.5 bg-slate-100 rounded-full"></span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-5 text-right text-left text-left">
                                    <div class="flex items-center justify-end space-x-2 text-left">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="p-2 text-slate-300 hover:text-sky-500 transition-colors text-left text-left">
                                            <svg class="w-5 h-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        
                                        <button type="button" @click="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="p-2 text-slate-300 hover:text-rose-500 transition-colors text-left text-left">
                                            <svg class="w-5 h-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>

                                        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden text-left">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-10 py-32 text-center bg-slate-50/20 text-left">
                                    <p class="text-slate-400 font-black uppercase tracking-widest text-left">Zero Items Registered</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50 text-left">
                {{ $products->links() }}
            </div>
        </div>
    </div>

<script>
function productManagement() {
    return {
        deletedProducts: [],
        
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },
        
        confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Terminate Item?',
                text: `Hapus "${productName}" secara permanen?`,
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
                    this.deletedProducts.push(productId);
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