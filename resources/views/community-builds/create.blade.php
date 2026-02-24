@extends('layouts.app')

@section('title', 'Share Your Build - Pitocom Community')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    
    <div class="mb-10 text-left">
        <a href="{{ route('community-builds.index') }}" class="text-sm font-bold text-sky-500 hover:underline">&larr; Back to Gallery</a>
        <h1 class="text-4xl font-black text-slate-900 mt-2 tracking-tight">Share Your <span class="text-sky-500">PC Masterpiece</span></h1>
        <p class="text-slate-500 mt-1">Ceritakan perjuanganmu merakit PC ini dan bantu orang lain terinspirasi.</p>
    </div>

    <form action="{{ route('community-builds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-8 text-left">
            
            {{-- 1. Informasi Dasar --}}
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Build Title</label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}" 
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-800"
                           placeholder="e.g. The Midnight Beast v2.0">
                </div>
                <div>
                    <label for="description" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">The Story (Optional)</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium"
                              placeholder="Ceritakan spesifikasi singkat atau hal tersulit saat merakit PC ini..."></textarea>
                </div>
            </div>

            {{-- 2. Photo Upload --}}
            <div x-data="{ photoPreview: null }">
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Show Off Your Build (Photo)</label>
                <div class="group relative w-full aspect-video bg-slate-50 rounded-[2rem] overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center">
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!photoPreview">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-bold text-slate-400">Click or drag image here</p>
                        </div>
                    </template>
                    <input type="file" name="image" required class="absolute inset-0 opacity-0 cursor-pointer"
                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                </div>
            </div>

            {{-- 3. Tag Purchased Products --}}
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Tag Components Used</label>
                <p class="text-xs text-slate-400 mb-4 italic">Pilih komponen yang kamu beli di Pitocom untuk build ini:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($userProducts as $product)
                        <label class="flex items-center p-4 bg-slate-50 rounded-2xl cursor-pointer hover:bg-sky-50 transition-colors border border-transparent peer-checked:border-sky-500">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="w-5 h-5 rounded text-sky-500 focus:ring-sky-500 mr-4">
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-sm truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $product->category->name }}</p>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full p-6 bg-slate-50 rounded-2xl text-center">
                            <p class="text-sm text-slate-500 italic">Kamu belum memiliki riwayat pembelian produk yang selesai.</p>
                        </div>
                    @endforelse
                </div>
                @error('product_ids') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-4">
                <button type="submit" class="w-full py-5 bg-sky-500 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-sky-100 hover:bg-sky-600 transition-all active:scale-[0.98]">
                    Submit Build for Review
                </button>
                <p class="text-center text-xs text-slate-400 mt-4 italic">Postinganmu akan muncul di galeri setelah disetujui oleh admin.</p>
            </div>

        </div>
    </form>
</div>
@endsection
