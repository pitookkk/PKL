@extends('layouts.app')

@section('title', 'Edit Category - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

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
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ initObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
    }}" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left"> 
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-colors mb-6 group text-left">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to Categories Hub
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase text-left">Edit <span class="text-sky-500">Category.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Ubah informasi kategori untuk menyesuaikan struktur katalog produk Anda.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="text-left">
        @csrf
        @method('PUT')
        
        <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-16 space-y-12 relative overflow-hidden group text-left">
            <div class="absolute -right-10 -top-10 bg-sky-500/5 w-40 h-40 rounded-full group-hover:scale-150 transition-transform duration-1000 text-left"></div>
            
            {{-- Form Inputs --}}
            <div class="space-y-10 relative text-left">
                {{-- Category Name --}}
                <div class="text-left">
                    <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1 text-left">Category Identity</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           placeholder="e.g. PC GAMING BUILDS"
                           class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 text-left text-lg shadow-inner transition-all uppercase tracking-tight">
                    @error('name') <span class="text-red-500 text-[10px] font-black mt-2 block uppercase tracking-tighter ml-2 text-left">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                    {{-- Parent Category --}}
                    <div class="text-left">
                        <label for="parent_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1 text-left text-left">Mapping Level</label>
                        <div class="relative text-left">
                            <select name="parent_id" id="parent_id" 
                                    class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 shadow-inner appearance-none cursor-pointer text-left">
                                <option value="">None (Top Level Category)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-slate-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Icon Indicator --}}
                    <div class="text-left">
                        <label for="icon_path" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1 text-left">Visual Indicator</label>
                        <div class="flex items-center space-x-4 text-left">
                            <div class="w-16 h-16 bg-slate-50 text-sky-500 rounded-2xl flex items-center justify-center text-2xl font-black shadow-inner border border-slate-100 shrink-0 text-left">
                                {{ substr($category->name, 0, 1) }}
                            </div>
                            <input type="text" name="icon_path" id="icon_path" value="{{ old('icon_path', $category->icon_path) }}"
                                   placeholder="e.g. cpu-icon or custom-svg-path"
                                   class="flex-1 px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner text-left">
                        </div>
                    </div>
                </div>

                <div class="pt-6 text-left">
                    <p class="text-[10px] text-slate-300 font-black uppercase tracking-[0.3em] pl-1 text-left">Gunakan class name ikon atau inisial akan otomatis digunakan sebagai penanda visual.</p>
                </div>
            </div>

            {{-- Submit Hub --}}
            <div class="pt-10 border-t border-slate-50 space-y-4 text-left">
                {{-- FIX: Teks Update Category Center --}}
                <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-[0.97] text-xs flex justify-center items-center text-center">
                    Update Category Info
                </button>
                <a href="{{ route('admin.categories.index') }}" class="block w-full text-center py-5 bg-slate-50 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] rounded-[2.5rem] hover:bg-slate-200 transition-all flex justify-center items-center">
                    Discard Changes
                </a>
            </div>
        </div>
    </form>
</div>
@endsection