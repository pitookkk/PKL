@extends('layouts.app')

@section('title', 'Edit Product - Pitocom Admin')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
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

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="productForm({{ json_encode($product->specifications ?? []) }}, {{ json_encode($product->variations ?? []) }})" 
     x-init="initObserver()">

    {{-- Breadcrumb & Title Section --}}
    <div class="reveal-section mb-12 text-left">
        <nav class="flex mb-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
            <a href="{{ route('admin.products.index') }}" class="hover:text-sky-500 transition">Inventory</a>
            <span class="mx-3 text-slate-200">/</span>
            <span class="text-sky-500">Modify Hardware</span>
        </nav>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter flex items-center">
            <span class="bg-sky-500/10 text-sky-500 p-3 rounded-2xl mr-6 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            </span>
            Edit: <span class="text-sky-500 ml-3">{{ $product->name }}</span>
        </h1>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-10 text-left">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Kolom Utama --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- 1. Core Information --}}
                <div class="reveal-section bg-white p-10 lg:p-12 rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 bg-sky-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="flex items-center mb-10 relative">
                        <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Core Information</h2>
                    </div>

                    <div class="space-y-10 relative">
                        <div>
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Product Identity</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}"
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-base">
                        </div>

                        <div>
                            <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Technical Story & Specs</label>
                            <textarea name="description" id="description" rows="6" required
                                      class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner leading-relaxed text-base">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                            <div>
                                <label for="base_price" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Price (Rp)</label>
                                <input type="number" name="base_price" id="base_price" required value="{{ old('base_price', $product->base_price) }}" 
                                       class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 shadow-inner text-xl">
                            </div>
                            <div>
                                <label for="stock" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Master Stock</label>
                                <input type="number" name="stock" id="stock" required value="{{ old('stock', $product->stock) }}" 
                                       class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-900 shadow-inner text-base">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Hardware Variations --}}
                <div class="reveal-section bg-white p-10 lg:p-12 rounded-[3.5rem] shadow-sm border border-slate-100" style="transition-delay: 150ms">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
                        <div class="flex items-center">
                            <div class="bg-amber-100 text-amber-600 p-3 rounded-2xl mr-4 shadow-sm border border-amber-200 text-left">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10"/></svg>
                            </div>
                            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Hardware Variations</h2>
                        </div>
                        <button type="button" @click="addVariation()" 
                                class="px-6 py-3 bg-slate-900 text-white rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95 text-left">
                            + Add Option
                        </button>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(v, index) in variations" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 items-end animate-fade-in-up relative text-left">
                                <div class="md:col-span-4 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 block">Label</label>
                                    <input type="text" x-model="v.name" :name="`variations[${index}][name]`" 
                                           class="w-full px-5 py-3.5 bg-white border-none rounded-2xl text-xs font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-sky-500 text-left">
                                </div>
                                <div class="md:col-span-3 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 block text-left">Price +</label>
                                    <input type="number" x-model="v.price" :name="`variations[${index}][price]`" 
                                           class="w-full px-5 py-3.5 bg-white border-none rounded-2xl text-xs font-black text-sky-600 shadow-sm focus:ring-2 focus:ring-sky-500 text-left">
                                </div>
                                <div class="md:col-span-3 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 block text-left">Stock</label>
                                    <input type="number" x-model="v.stock" :name="`variations[${index}][stock]`" 
                                           class="w-full px-5 py-3.5 bg-white border-none rounded-2xl text-xs font-black text-slate-900 shadow-sm focus:ring-2 focus:ring-sky-500 text-left">
                                </div>
                                <div class="md:col-span-2 flex justify-end">
                                    <button type="button" @click="removeVariation(index)" class="bg-rose-50 text-rose-400 p-3.5 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-rose-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- 3. Technical Specs --}}
                <div class="reveal-section bg-white p-10 lg:p-12 rounded-[3.5rem] shadow-sm border border-slate-100" style="transition-delay: 200ms">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
                        <div class="flex items-center text-left">
                            <div class="bg-sky-100 text-sky-600 p-3 rounded-2xl mr-4 shadow-sm border border-sky-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Technical Attributes</h2>
                        </div>
                        <button type="button" @click="addSpec()" 
                                class="px-6 py-3 bg-slate-900 text-white rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95 text-left">
                            + Add Spec
                        </button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(spec, index) in specifications" :key="index">
                            <div class="flex gap-4 animate-fade-in-up items-center bg-slate-50 p-6 rounded-3xl border border-slate-100 text-left">
                                <div class="flex-1 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 block text-left">Label</label>
                                    <input type="text" x-model="spec.label" :name="`specifications[${index}][label]`" 
                                           class="w-full px-5 py-3 bg-white border-none rounded-2xl text-xs font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-sky-500 text-left">
                                </div>
                                <div class="flex-1 text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 block text-left">Value</label>
                                    <input type="text" x-model="spec.value" :name="`specifications[${index}][value]`" 
                                           class="w-full px-5 py-3 bg-white border-none rounded-2xl text-xs font-bold text-slate-500 shadow-sm focus:ring-2 focus:ring-sky-500 text-left">
                                </div>
                                <button type="button" @click="removeSpec(index)" class="bg-rose-50 text-rose-400 p-3.5 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm mt-5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Kolom Sidebar --}}
            <div class="space-y-10 text-left">
                {{-- Logistics Hub --}}
                <div class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 text-left" style="transition-delay: 300ms">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-10 text-left">Logistics Hub</h3>
                    <div class="space-y-10 text-left">
                        <div class="text-left">
                            <label for="category_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Active Category</label>
                            <div class="relative">
                                <select name="category_id" id="category_id" required x-model="selectedCategory"
                                        class="w-full px-6 py-5 bg-slate-50 border-none rounded-[1.5rem] focus:ring-2 focus:ring-sky-500 font-black text-slate-700 shadow-inner cursor-pointer appearance-none">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-6 pointer-events-none text-slate-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="text-left">
                            <label for="brand" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Manufacturer</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" 
                                   class="w-full px-6 py-5 bg-slate-50 border-none rounded-[1.5rem] focus:ring-2 focus:ring-sky-500 font-black text-slate-700 shadow-inner text-left">
                        </div>

                        <div class="pt-6 border-t border-slate-50">
                            <label class="flex items-center cursor-pointer group text-left">
                                <div class="relative">
                                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer" @checked(old('is_featured', $product->is_featured))>
                                    <div class="w-14 h-7 bg-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-400 shadow-inner"></div>
                                </div>
                                <div class="ml-5 text-left">
                                    <span class="block text-[10px] font-black text-slate-900 uppercase tracking-widest group-hover:text-amber-500 transition-colors text-left">Featured Status</span>
                                    <span class="text-[9px] font-bold italic {{ $product->is_featured ? 'text-emerald-500' : 'text-slate-400' }}">Currently {{ $product->is_featured ? 'Active' : 'Disabled' }}</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- PC Compatibility Helper --}}
                <div class="reveal-section p-10 rounded-[3rem] shadow-2xl shadow-sky-500/5 border border-sky-100 bg-sky-50/20 transition-all duration-700 text-left" 
                     x-show="isPcHardware()" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <h3 class="text-[10px] font-black text-sky-500 uppercase tracking-[0.4em] mb-8 flex items-center text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9.75 17L9 20l-1 1h6l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Logic Helper
                    </h3>
                    <div class="space-y-8 text-left">
                        <div class="text-left">
                            <label class="block text-[9px] font-black text-sky-400 uppercase tracking-widest mb-2 ml-1 text-left">Infrastructure</label>
                            <input type="text" name="socket_type" value="{{ old('socket_type', $product->socket_type) }}" 
                                   class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-slate-700 shadow-sm text-left">
                        </div>
                        <div class="text-left">
                            <label class="block text-[9px] font-black text-sky-400 uppercase tracking-widest mb-2 ml-1 text-left">Memory Standard</label>
                            <input type="text" name="ram_type" value="{{ old('ram_type', $product->ram_type) }}" 
                                   class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-slate-700 shadow-sm text-left">
                        </div>
                        <div class="text-left">
                            <label class="block text-[9px] font-black text-sky-400 uppercase tracking-widest mb-2 ml-1 text-left">Power Draw (W)</label>
                            <input type="number" name="wattage_requirement" value="{{ old('wattage_requirement', $product->wattage_requirement ?? 0) }}"
                                   class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 text-xs font-black text-sky-600 shadow-sm text-left">
                        </div>
                    </div>
                </div>

                {{-- Visual Master --}}
                <div class="reveal-section bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 text-left" style="transition-delay: 400ms">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-10 text-left">Visual Hub</h3>
                    <div x-data="{ photoPreview: null }" class="text-left">
                        <div class="group relative w-full aspect-square bg-slate-50 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center transition-all hover:border-sky-300 shadow-inner">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!photoPreview">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest text-left">Replace Photo</p>
                                    </div>
                                @endif
                            </template>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        </div>
                    </div>
                </div>

                <div class="reveal-section space-y-4" style="transition-delay: 500ms">
                    <button type="submit" class="w-full bg-slate-900 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-sky-500 transition-all active:scale-95 text-xs text-center">
                        Sync Changes
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="block w-full text-center py-5 bg-slate-100 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] rounded-[2rem] hover:bg-slate-200 transition-all text-center">Cancel Session</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function productForm(initialSpecs = [], initialVariations = []) {
    let specArray = [];
    if (initialSpecs && typeof initialSpecs === 'object') {
        for (const [label, value] of Object.entries(initialSpecs)) {
            specArray.push({ label: label, value: value });
        }
    }

    return {
        selectedCategory: '{{ $product->category_id }}',
        specifications: specArray,
        variations: initialVariations.map(v => ({
            name: v.variation_name,
            sku: v.sku,
            price: v.additional_price,
            stock: v.stock
        })),
        
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },

        addSpec() { this.specifications.push({ label: '', value: '' }); },
        removeSpec(index) { this.specifications.splice(index, 1); },
        addVariation() { this.variations.push({ name: '', sku: '', price: 0, stock: 0 }); },
        removeVariation(index) { this.variations.splice(index, 1); },
        
        isPcHardware() {
            const select = document.getElementById('category_id');
            if (!select || select.selectedIndex === -1) return false;
            const selectedOption = select.options[select.selectedIndex];
            const slug = selectedOption.getAttribute('data-slug');
            return ['processor', 'motherboard', 'ram', 'power-supply', 'case', 'vga'].includes(slug);
        }
    }
}
</script>
@endsection