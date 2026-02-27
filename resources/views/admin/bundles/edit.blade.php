@extends('layouts.app')

@section('title', 'Edit Bundle - Pitocom Admin')

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
     x-data="bundleComponent" 
     x-init="
        initObserver();
        allProducts = JSON.parse($el.getAttribute('data-all-products'));
        selectedProducts = JSON.parse($el.getAttribute('data-current-products')).map(p => ({
            id: p.id,
            quantity: p.pivot ? p.pivot.quantity : 1
        }));
     "
     data-all-products="{{ json_encode($products) }}"
     data-current-products="{{ json_encode($bundle->products) }}">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-colors mb-6 group text-left">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to Bundles Hub
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase text-left">Modify <span class="text-sky-500">Bundle.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Perbarui informasi, harga, dan komposisi item dalam paket eksklusif ini.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full text-left"></div>
    </div>

    <form action="{{ route('admin.bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            {{-- Kolom Kiri: Form Utama --}}
            <div class="lg:col-span-2 space-y-10 text-left">
                {{-- 1. Package Identity --}}
                <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-12 space-y-10 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 bg-sky-500/5 w-32 h-32 rounded-full group-hover:scale-150 transition-transform duration-1000 text-left"></div>
                    
                    <div class="flex items-center mb-4 relative text-left">
                        <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg text-left">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left text-left">Package Identity</h2>
                    </div>

                    <div class="space-y-8 relative text-left">
                        <div class="text-left text-left">
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left">Bundle Display Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $bundle->name) }}" 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-800 shadow-inner text-base transition-all text-left">
                        </div>
                        <div class="text-left text-left">
                            <label for="description" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left text-left">Value Proposition Description</label>
                            <textarea name="description" id="description" rows="4" required
                                      class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-600 shadow-inner leading-relaxed text-base text-left">{{ old('description', $bundle->description) }}</textarea>
                        </div>
                        <div class="text-left text-left">
                            <label for="price" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left text-left">Special Bundle Price (IDR)</label>
                            <input type="number" name="price" id="price" required value="{{ old('price', $bundle->price) }}" 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 text-3xl shadow-inner tracking-tighter text-left">
                        </div>
                    </div>
                </div>

                {{-- 2. Items Inclusion --}}
                <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 lg:p-12 text-left" style="transition-delay: 150ms">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4 text-left">
                        <div class="flex items-center text-left text-left">
                            <div class="bg-amber-100 text-amber-600 p-3 rounded-2xl mr-4 shadow-sm border border-amber-200 text-left">
                                <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left text-left">Hardware Composition</h3>
                        </div>
                        <button type="button" @click="addProductRow()" 
                                class="px-6 py-3 bg-slate-900 text-white rounded-[1.2rem] font-black text-[10px] uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95 text-left">
                            + Add Hardware Item
                        </button>
                    </div>

                    <div class="space-y-6 text-left">
                        <template x-for="(row, index) in selectedProducts" :key="index">
                            <div class="flex flex-col md:flex-row gap-6 items-end bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 animate-fade-in-up text-left">
                                <div class="flex-1 text-left text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 block text-left">Select Product Assets</label>
                                    <div class="relative text-left text-left">
                                        <select :name="`products[${index}][id]`" x-model="row.id" required
                                                class="w-full px-5 py-4 bg-white border-none rounded-2xl text-sm font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-sky-500 appearance-none text-left">
                                            <option value="">-- Choose Stock Item --</option>
                                            <template x-for="p in allProducts" :key="p.id">
                                                <option :value="p.id" x-text="p.name" :selected="p.id == row.id"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-300 text-left">
                                            <svg class="w-4 h-4 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full md:w-32 text-left text-left">
                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 block text-left text-left text-left">Quantity</label>
                                    <input type="number" :name="`products[${index}][quantity]`" x-model="row.quantity" min="1" required
                                           class="w-full px-5 py-4 bg-white border-none rounded-2xl text-sm font-black text-slate-700 shadow-sm focus:ring-2 focus:ring-sky-500 text-center text-left">
                                </div>
                                <button type="button" @click="removeProductRow(index)" class="bg-rose-50 text-rose-400 p-4 rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-rose-100 text-left text-left">
                                    <svg class="w-5 h-5 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Media & Status --}}
            <div class="space-y-10 text-left">
                {{-- Image Hub --}}
                <div class="reveal-section bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10 text-left" style="transition-delay: 300ms">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-10 text-left text-left text-left">Visual Assets</h3>
                    <div x-data="{ photoPreview: null }" class="text-left text-left">
                        <div class="group relative w-full aspect-square bg-slate-50 rounded-[2.5rem] overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center transition-all hover:border-sky-300 hover:bg-white shadow-inner text-left text-left text-left text-left">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover text-left text-left">
                            </template>
                            <template x-if="!photoPreview">
                                @if($bundle->image_path)
                                    <img src="{{ asset('storage/' . $bundle->image_path) }}" class="w-full h-full object-cover text-left text-left">
                                @else
                                    <div class="text-center text-left text-left">
                                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-4 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest text-left text-left text-left">Replace Media</p>
                                    </div>
                                @endif
                            </template>
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer text-left text-left"
                                   @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        </div>
                    </div>
                </div>

                {{-- Status Logic --}}
                <div class="reveal-section bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-left" style="transition-delay: 400ms">
                    <div class="flex items-center justify-between text-left text-left">
                        <div class="text-left text-left">
                            <p class="text-xs font-black text-slate-900 uppercase tracking-widest text-left text-left">Public Status</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter mt-1 text-left text-left">Visible in showroom</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer text-left text-left">
                            <input type="checkbox" name="is_active" value="1" @checked($bundle->is_active) class="sr-only peer text-left text-left">
                            <div class="w-14 h-7 bg-slate-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-sky-500 shadow-inner text-left text-left"></div>
                        </label>
                    </div>
                </div>

                {{-- Actions Session --}}
                <div class="reveal-section space-y-4 text-left" style="transition-delay: 500ms">
                    {{-- FIX: Tombol diperlebar, teks Center dan tidak mepet --}}
                    <button type="submit" class="w-full py-6 bg-sky-500 text-white rounded-[2rem] font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-slate-900 transition-all active:scale-95 text-xs flex justify-center items-center">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.bundles.index') }}" class="block w-full text-center py-5 bg-slate-100 text-slate-400 font-black uppercase tracking-[0.2em] text-[10px] rounded-[2rem] hover:bg-slate-200 transition-all text-center">Discard Session</a>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('bundleComponent')) {
            Alpine.data('bundleComponent', () => ({
                allProducts: [],
                selectedProducts: [],
                
                initObserver() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) entry.target.classList.add('visible');
                        });
                    }, { threshold: 0.1 });
                    document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
                },

                addProductRow() { this.selectedProducts.push({ id: '', quantity: 1 }); },
                removeProductRow(index) { this.selectedProducts.splice(index, 1); }
            }));
        }
    });
</script>
@endsection