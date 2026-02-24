@extends('layouts.app')

@section('title', 'Products Management - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="productForm()">
    {{-- Breadcrumb & Title Section --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <nav class="flex mb-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
            <a href="{{ route('admin.products.index') }}" class="hover:text-sky-500 transition">Inventory</a>
            <span class="mx-3 text-slate-200">/</span>
            <span class="text-sky-500">Add New Product</span>
        </nav>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight flex items-center">
            <span class="bg-sky-500/10 text-sky-500 p-3 rounded-2xl mr-5 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            </span>
            Create New Product
        </h1>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10 text-left">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Kolom Utama --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- 1. Informasi Dasar --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex items-center mb-8">
                        <div class="bg-sky-100 text-sky-600 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Informasi Utama</h2>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nama Produk</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. PC Gaming Pitocom Pro"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                        </div>
                        <div>
                            <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Deskripsi Produk</label>
                            <textarea name="description" id="description" rows="6" required placeholder="Detail spesifikasi dan keunggulan..."
                                      class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner leading-relaxed">{{ old('description') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label for="base_price" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Harga Dasar (Rp)</label>
                                <input type="number" name="base_price" id="base_price" required value="{{ old('base_price') }}" 
                                       class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 shadow-inner text-lg">
                            </div>
                            <div>
                                <label for="stock" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Stok Master</label>
                                <input type="number" name="stock" id="stock" required value="{{ old('stock') }}" 
                                       class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-slate-900 shadow-inner">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Variasi Produk --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <div class="bg-amber-100 text-amber-600 p-2 rounded-xl mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10"/></svg>
                            </div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest text-left">Variasi Produk</h2>
                        </div>
                        <button type="button" @click="addVariation()" 
                                class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-lg active:scale-95">
                            + Tambah Variasi
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(v, index) in variations" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-6 bg-slate-50 rounded-[2rem] border border-slate-100 items-end animate-fade-in-up">
                                <div class="md:col-span-4 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 block">Nama Variasi</label>
                                    <input type="text" x-model="v.name" :name="`variations[${index}][name]`" placeholder="e.g. RAM 16GB" 
                                           class="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="md:col-span-3 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 block">Harga Extra (+)</label>
                                    <input type="number" x-model="v.price" :name="`variations[${index}][price]`" 
                                           class="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-black text-sky-600 shadow-sm focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="md:col-span-3 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 block">Stok</label>
                                    <input type="number" x-model="v.stock" :name="`variations[${index}][stock]`" 
                                           class="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-black text-slate-900 shadow-sm focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="md:col-span-2 flex justify-end">
                                    <button type="button" @click="removeVariation(index)" class="bg-rose-50 text-rose-400 p-3 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="variations.length === 0">
                            <div class="text-center py-10 border-2 border-dashed border-slate-100 rounded-[2rem]">
                                <p class="text-slate-400 font-bold italic text-sm">Belum ada variasi. Klik tombol di atas untuk menambah pilihan.</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- 3. Spesifikasi Detail --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center text-left text-left">
                            <div class="bg-sky-100 text-sky-600 p-2 rounded-xl mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest text-left text-left">Spesifikasi Dinamis</h3>
                        </div>
                        <button type="button" @click="addSpec()" 
                                class="px-5 py-2.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-lg active:scale-95 text-left">
                            + Tambah Atribut
                        </button>
                    </div>
                    <div class="space-y-4 text-left">
                        <template x-for="(spec, index) in specifications" :key="index">
                            <div class="flex gap-4 animate-fade-in-up items-center">
                                <div class="flex-1">
                                    <input type="text" x-model="spec.label" :name="`specifications[${index}][label]`" placeholder="Label (e.g. Chipset)" 
                                           class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-700 shadow-inner focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div class="flex-1">
                                    <input type="text" x-model="spec.value" :name="`specifications[${index}][value]`" placeholder="Value (e.g. Z790)" 
                                           class="w-full px-5 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-500 shadow-inner focus:ring-2 focus:ring-sky-500">
                                </div>
                                <button type="button" @click="removeSpec(index)" class="bg-rose-50 text-rose-400 p-3 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Kolom Sidebar --}}
            <div class="space-y-10 text-left">
                {{-- Organisasi --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Data Organisasi</h3>
                    <div class="space-y-8">
                        <div>
                            <label for="category_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kategori</label>
                            <select name="category_id" id="category_id" required x-model="selectedCategory"
                                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner cursor-pointer">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-slug="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="brand" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Brand Hardware</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand') }}" placeholder="ASUS, MSI, etc."
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner">
                        </div>
                        {{-- TOMBOL FEATURED KEMBALI DI SINI --}}
                        <div class="pt-4 border-t border-slate-50">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500 shadow-inner"></div>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-[10px] font-black text-slate-900 uppercase tracking-widest group-hover:text-sky-500 transition-colors">Featured Product</span>
                                    <span class="text-[9px] text-slate-400 font-bold italic">Tampilkan di halaman utama</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- PC Builder Helper (Conditional Styling) --}}
                <div class="p-10 rounded-[2.5rem] shadow-sm border border-sky-100 bg-sky-50/30 transition-all duration-500" x-show="isPcHardware()" x-cloak>
                    <h3 class="text-sm font-black text-sky-900 mb-6 flex items-center uppercase tracking-widest">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h6l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        PC Compatibility
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black text-sky-600 uppercase tracking-widest mb-1 ml-1 text-left">Socket Type</label>
                            <input type="text" name="socket_type" placeholder="e.g. LGA1700"
                                   class="w-full px-4 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-slate-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-sky-600 uppercase tracking-widest mb-1 ml-1 text-left">RAM Type</label>
                            <input type="text" name="ram_type" placeholder="e.g. DDR5"
                                   class="w-full px-4 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-slate-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[9px] font-black text-sky-600 uppercase tracking-widest mb-1 ml-1 text-left">Power (W)</label>
                            <input type="number" name="wattage_requirement" value="0"
                                   class="w-full px-4 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-slate-700 shadow-sm text-left">
                        </div>
                    </div>
                </div>

                {{-- Gambar --}}
                <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Cover Gallery</h3>
                    <div x-data="{ photoPreview: null }" class="text-left text-left">
                        <div class="group relative w-full aspect-square bg-slate-50 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center transition-all hover:border-sky-300">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!photoPreview">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-slate-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-tighter">Upload Photo</p>
                                </div>
                            </template>
                            <input type="file" name="image" required class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-sky-500 transition-all active:scale-95 text-xs text-center text-center">
                        Publish to Pitocom
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="block w-full text-center py-5 bg-slate-100 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] rounded-[2rem] hover:bg-slate-200 transition-all text-center">Discard Changes</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function productForm() {
    return {
        selectedCategory: '',
        specifications: [],
        variations: [],
        addSpec() { this.specifications.push({ label: '', value: '' }); },
        removeSpec(index) { this.specifications.splice(index, 1); },
        addVariation() { this.variations.push({ name: '', sku: '', price: 0, stock: 0 }); },
        removeVariation(index) { this.variations.splice(index, 1); },
        isPcHardware() {
            if (!this.selectedCategory) return false;
            const select = document.getElementById('category_id');
            const selectedOption = Array.from(select.options).find(opt => opt.value == this.selectedCategory);
            if (!selectedOption) return false;
            const slug = selectedOption.getAttribute('data-slug');
            return ['processor', 'motherboard', 'ram', 'power-supply', 'case'].includes(slug);
        }
    }
}
</script>
@endsection