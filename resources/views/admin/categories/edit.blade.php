@extends('layouts.app')

@section('title', 'Edit Category: ' . $category->name . ' - Pitocom Admin')

@section('content')
    {{-- Memastikan Navbar Admin Terpanggil --}}
    @include('admin.partials.nav')

    <div class="max-w-3xl mx-auto px-4 py-10">
        {{-- Breadcrumb & Title --}}
        <div class="mb-10 animate-fade-in-up text-left">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm font-bold text-sky-500 hover:text-sky-600 transition mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Categories
            </a>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Edit Category</h1>
            <p class="text-slate-500 font-medium">Ubah informasi kategori untuk menyesuaikan katalog produk Anda.</p>
        </div>

        {{-- Edit Form Card --}}
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-8 animate-fade-in-up text-left">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                {{-- Category Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           placeholder="e.g. High-End Components"
                           class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                    @error('name') <span class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                </div>
                
                {{-- Parent Category --}}
                <div>
                    <label for="parent_id" class="block text-sm font-bold text-slate-700 mb-2">Parent Category (Optional)</label>
                    <div class="relative">
                        <select name="parent_id" id="parent_id" 
                                class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all appearance-none cursor-pointer text-slate-700">
                            <option value="">None (Top Level Category)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                
                {{-- Icon Path --}}
                <div>
                    <label for="icon_path" class="block text-sm font-bold text-slate-700 mb-2">Category Icon Indicator</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-sky-50 text-sky-500 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-sm border border-sky-100">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <input type="text" name="icon_path" id="icon_path" value="{{ old('icon_path', $category->icon_path) }}"
                               placeholder="e.g. monitor-icon or SVG code"
                               class="flex-1 px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                    </div>
                    <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider">Gunakan class name ikon atau inisial akan otomatis digunakan.</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <button type="submit" class="w-full sm:flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95">
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto px-8 py-4 text-slate-400 font-bold text-sm rounded-2xl hover:bg-slate-50 hover:text-slate-600 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>