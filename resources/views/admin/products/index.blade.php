@extends('layouts.app')

@section('title', 'Manage Products - Pitocom Admin')

@section('content')
    {{-- Navbar Khusus Admin --}}
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Manage Products</h1>
                <p class="text-slate-500 font-medium">Pantau dan kelola katalog produk hardware kamu.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center justify-center bg-sky-500 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-sky-100 hover:bg-sky-600 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Image</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Product Info</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Price</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Inventory</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Featured</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($products as $product)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="relative w-16 h-16 overflow-hidden rounded-2xl shadow-sm border border-slate-100">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/80' }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 text-base leading-tight">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-400 font-medium mt-1">Brand: {{ $product->brand ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-sky-50 text-sky-600 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-sky-100">
                                        {{ $product->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span class="font-bold {{ $product->stock < 5 ? 'text-red-500' : 'text-slate-700' }}">
                                            {{ $product->stock }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 ml-1 font-bold uppercase">Units</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->is_featured)
                                        <span class="inline-block w-2 h-2 bg-sky-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(14,165,233,0.5)]"></span>
                                    @else
                                        <span class="inline-block w-2 h-2 bg-slate-200 rounded-full"></span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="p-2 text-slate-400 hover:text-sky-500 hover:bg-sky-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="text-6xl mb-4">🔍</div>
                                    <p class="text-slate-400 font-bold text-lg">No products found.</p>
                                    <p class="text-slate-300 text-sm mt-1">Mulai tambahkan produk pertamamu sekarang.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Styling --}}
            <div class="px-6 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection