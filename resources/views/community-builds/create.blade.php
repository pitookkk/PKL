@extends('layouts.app')

@section('title', 'Share Your Build - Pitocom Community')

@section('content')

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
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" x-data="{ initObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
}}" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <a href="{{ route('community-builds.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-colors mb-6 group text-left">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to Showcase Hub
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase text-left">Share Your <span class="text-sky-500">Masterpiece.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Ceritakan perjuanganmu merakit PC ini dan bantu orang lain terinspirasi.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    <form action="{{ route('community-builds.store') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto text-left">
        @csrf
        
        <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-16 space-y-12 relative overflow-hidden group text-left">
            <div class="absolute -right-10 -top-10 bg-sky-500/5 w-40 h-40 rounded-full group-hover:scale-150 transition-transform duration-1000 text-left"></div>
            
            {{-- 1. Informasi Dasar --}}
            <div class="space-y-10 relative text-left">
                <div class="flex items-center mb-4 text-left">
                    <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg text-left">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Build credentials</h2>
                </div>

                <div class="grid grid-cols-1 gap-10 text-left">
                    <div class="text-left">
                        <label for="title" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1 text-left">Identity Name</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}" 
                               class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 uppercase shadow-inner text-base tracking-widest transition-all text-left"
                               placeholder="e.g. THE MIDNIGHT BEAST V2.0">
                    </div>

                    <div class="text-left">
                        <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 ml-1 text-left text-left">Architect's Story (Optional)</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-8 py-6 bg-slate-50 border-none rounded-[2.5rem] focus:ring-2 focus:ring-sky-500 font-medium text-slate-700 shadow-inner text-base leading-relaxed text-left"
                                  placeholder="Ceritakan spesifikasi singkat atau hal tersulit saat merakit PC ini..."></textarea>
                    </div>
                </div>
            </div>

            {{-- 2. Photo Upload Hub --}}
            <div class="space-y-10 relative text-left" x-data="{ photoPreview: null }">
                <div class="flex items-center mb-4 text-left">
                    <div class="bg-sky-50 text-sky-500 p-3 rounded-2xl mr-4 shadow-sm border border-sky-100 text-left">
                        <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Visual Asset Capture</h2>
                </div>

                <div class="group relative w-full aspect-video bg-slate-50 rounded-[3rem] overflow-hidden border-4 border-dashed border-slate-100 hover:border-sky-200 transition-all duration-700 flex items-center justify-center shadow-inner text-left">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!photoPreview">
                        <div class="text-center group-hover:scale-110 transition-transform duration-500">
                            <div class="w-20 h-20 bg-white rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-xl text-slate-200 group-hover:text-sky-500 transition-colors">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Deploy Image Asset Here</p>
                            <p class="text-[9px] text-slate-300 font-bold mt-2 italic uppercase">Click or Drag and Drop</p>
                        </div>
                    </template>
                    <input type="file" name="image" required class="absolute inset-0 opacity-0 cursor-pointer z-20 text-left"
                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                </div>
            </div>

            {{-- 3. Tag Purchased Products --}}
            <div class="space-y-10 relative text-left text-left">
                <div class="flex items-center mb-4 text-left">
                    <div class="bg-emerald-50 text-emerald-600 p-3 rounded-2xl mr-4 shadow-sm border border-emerald-100 text-left">
                        <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Hardware invoicing tags</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    @forelse($userProducts as $product)
                        <label class="relative flex items-center p-6 bg-slate-50 rounded-[2rem] cursor-pointer hover:bg-white hover:shadow-xl transition-all duration-500 border border-transparent group">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                   class="w-6 h-6 rounded-xl text-sky-500 focus:ring-sky-500 mr-5 border-slate-200 transition-all cursor-pointer">
                            <div class="min-w-0 text-left">
                                <p class="font-black text-slate-800 text-sm truncate group-hover:text-sky-600 transition-colors text-left">{{ $product->name }}</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1 text-left">{{ $product->category->name }}</p>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full p-12 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-left">Zero purchase records found in your inventory.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Submit Hub --}}
            <div class="pt-10 border-t border-slate-50 space-y-6 text-left">
                <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2.5rem] font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-[0.97] text-xs flex justify-center items-center">
                    Submit Build for Intelligence Review
                </button>
                <p class="text-center text-[10px] text-slate-300 font-black uppercase tracking-[0.2em]">Postingan akan dienkripsi dan divalidasi oleh sistem admin sebelum publikasi.</p>
            </div>
        </div>
    </form>
</div>
@endsection