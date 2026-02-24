@extends('layouts.app')

@section('title', 'Create New Bundle')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')<div class="max-w-6xl mx-auto px-4 py-12" 
     x-data="bundleComponent" 
     x-init="allProducts = JSON.parse($el.getAttribute('data-all-products'))"
     data-all-products="{{ json_encode($products) }}">
    
    <div class="mb-10 text-left">
        <a href="{{ route('admin.bundles.index') }}" class="text-sm font-bold text-sky-500 hover:underline">&larr; Back to List</a>
        <h1 class="text-4xl font-black text-slate-900 mt-2 tracking-tight">Create <span class="text-sky-500">Package Bundle</span></h1>
        <p class="text-slate-500 mt-1 font-medium">Satukan beberapa produk menjadi satu paket harga spesial.</p>
    </div>

    <form action="{{ route('admin.bundles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-2 space-y-8 text-left">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10 space-y-6">
                    <div>
                        <label for="name" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Bundle Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-800">
                    </div>
                    <div>
                        <label for="description" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Package Description</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium text-slate-600"></textarea>
                    </div>
                    <div>
                        <label for="price" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Package Price (Rp)</label>
                        <input type="number" name="price" id="price" required value="{{ old('price') }}" 
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 text-2xl">
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Included Products</h3>
                        <button type="button" @click="addProductRow()" 
                                class="px-4 py-2 bg-sky-50 text-sky-600 rounded-xl font-bold text-xs hover:bg-sky-100 transition-colors">
                            + Add Product
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(row, index) in selectedProducts" :key="index">
                            <div class="flex gap-4 items-end bg-slate-50 p-4 rounded-2xl">
                                <div class="flex-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 block ml-1">Select Product</label>
                                    <select :name="`products[${index}][id]`" x-model="row.id" required
                                            class="w-full bg-white border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-sky-500">
                                        <option value="">-- Choose --</option>
                                        <template x-for="p in allProducts" :key="p.id">
                                            <option :value="p.id" x-text="p.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="w-24">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 block ml-1">Qty</label>
                                    <input type="number" :name="`products[${index}][quantity]`" x-model="row.quantity" min="1" required
                                           class="w-full bg-white border-none rounded-xl text-sm font-black text-slate-700 focus:ring-2 focus:ring-sky-500 text-center">
                                </div>
                                <button type="button" @click="removeProductRow(index)" class="text-slate-300 hover:text-rose-500 p-2 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                        <template x-if="selectedProducts.length === 0">
                            <p class="text-center py-10 text-slate-400 italic">No products added. Click the button above to start.</p>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-8 text-left">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Package Image</label>
                    <div x-data="{ photoPreview: null }" class="group relative w-full aspect-square bg-slate-50 rounded-[2rem] overflow-hidden border-2 border-dashed border-slate-200 flex items-center justify-center">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </template>
                        <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer"
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                    </div>
                </div>

                <div class="space-y-3">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-[2rem] font-black text-xl shadow-xl hover:bg-sky-500 transition-all active:scale-[0.98]">
                        Publish Bundle
                    </button>
                    <a href="{{ route('admin.bundles.index') }}" class="block w-full text-center py-4 bg-slate-50 text-slate-400 font-bold text-sm rounded-[2rem] hover:text-slate-600 transition-all">Cancel</a>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bundleComponent', () => ({
            allProducts: [],
            selectedProducts: [],
            
            addProductRow() {
                this.selectedProducts.push({ id: '', quantity: 1 });
            },

            removeProductRow(index) {
                this.selectedProducts.splice(index, 1);
            }
        }));
    });
</script>
@endsection
