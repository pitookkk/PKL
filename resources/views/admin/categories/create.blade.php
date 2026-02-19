@extends('layouts.app')

@section('title', 'Create New Category - Pitocom Admin')

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
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Create New Category</h1>
            <p class="text-slate-500 font-medium text-left">Tambahkan kategori baru untuk mengorganisir katalog hardware Anda.</p>
        </div>

        {{-- Create Form Card --}}
        <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-slate-100 space-y-8 animate-fade-in-up text-left">
            @csrf
            
            <div class="space-y-6">
                {{-- Category Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           placeholder="e.g. PC Gaming Builds"
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
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                    <label for="icon_path" class="block text-sm font-bold text-slate-700 mb-2">Icon Indicator (SVG or Class)</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-slate-50 text-slate-300 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-sm border border-slate-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="text" name="icon_path" id="icon_path" value="{{ old('icon_path') }}"
                               placeholder="e.g. cpu-icon or custom-svg-path"
                               class="flex-1 px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                    </div>
                    <p class="mt-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider">Ikon akan membantu pelanggan mengenali kategori secara visual di beranda.</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <button type="submit" class="w-full sm:flex-1 bg-slate-900 text-white py-4 rounded-2xl font-black shadow-xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95">
                    Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="w-full sm:w-auto px-8 py-4 text-slate-400 font-bold text-sm rounded-2xl hover:bg-slate-50 hover:text-slate-600 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection