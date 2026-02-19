@extends('layouts.app')

@section('title', 'Manage Categories - Pitocom Admin')

@section('content')
    {{-- Memastikan Navbar Admin Terpanggil --}}
    @include('admin.partials.nav')

    <div class="max-w-7xl mx-auto px-4 py-10">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10 animate-fade-in-up">
            <div class="text-left">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Product Categories</h1>
                <p class="text-slate-500 font-medium">Atur struktur kategori untuk mempermudah navigasi pelanggan.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" 
               class="inline-flex items-center justify-center bg-sky-500 text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-sky-100 hover:bg-sky-600 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Category
            </a>
        </div>

        {{-- Category Table Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-left">Category Name</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-left">Slug</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-left">Parent Category</th>
                            <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($categories as $category)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 text-left">
                                    <div class="flex items-center space-x-4">
                                        {{-- Icon Placeholder Dinamis --}}
                                        <div class="w-10 h-10 bg-sky-50 text-sky-500 rounded-xl flex items-center justify-center font-bold text-sm group-hover:bg-sky-500 group-hover:text-white transition-all">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-800 text-lg">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-left">
                                    <code class="text-xs bg-slate-100 px-3 py-1 rounded-lg text-slate-500 font-mono">{{ $category->slug }}</code>
                                </td>
                                <td class="px-8 py-6 text-left">
                                    @if($category->parent)
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-lg border border-slate-200">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-xs italic">No Parent</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="p-2 text-slate-400 hover:text-sky-500 hover:bg-sky-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
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
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="text-5xl mb-4">📂</div>
                                    <p class="text-slate-400 font-bold text-lg">No categories found.</p>
                                    <p class="text-slate-300 text-sm mt-1">Mulai organisir produk kamu dengan membuat kategori baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination Styling --}}
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection