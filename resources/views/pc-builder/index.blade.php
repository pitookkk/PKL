@extends('layouts.app')

@section('title', 'PC Builder - Pitocom Virtual Assembly')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12" x-data="pcBuilder()">
    
    {{-- Header --}}
    <div class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6 text-left">
        <div class="animate-fade-in-up">
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">PC <span class="text-sky-500">Builder</span></h1>
            <p class="text-slate-500 mt-2 font-medium">Rakit PC impianmu dengan panduan kompatibilitas otomatis.</p>
        </div>
        <div class="bg-slate-900 px-8 py-4 rounded-[2rem] shadow-2xl flex items-center space-x-6">
            <div class="text-right">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Total Estimated</p>
                <p class="text-3xl font-black text-sky-400 tracking-tighter" x-text="formatPrice(totalPrice)"></p>
            </div>
            <button @click="finishBuild()" 
                    class="bg-sky-500 hover:bg-sky-400 text-white font-black px-6 py-3 rounded-2xl transition-all shadow-lg active:scale-95 disabled:bg-slate-700 disabled:cursor-not-allowed"
                    :disabled="selectedComponents.length === 0">
                Finish Build
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
        
        {{-- 1. Steps Navigation (Left Sidebar) --}}
        <div class="lg:col-span-1 space-y-3">
            <template x-for="(step, index) in steps" :key="step.id">
                <div class="relative">
                    <button @click="currentStepIndex = index; fetchComponents()"
                            class="w-full flex items-center p-4 rounded-2xl transition-all duration-300 text-left group"
                            :class="currentStepIndex === index ? 'bg-white shadow-md border-l-4 border-sky-500' : 'hover:bg-slate-100'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs mr-4 shadow-sm"
                             :class="isStepSelected(step.id) ? 'bg-emerald-500 text-white' : (currentStepIndex === index ? 'bg-sky-500 text-white' : 'bg-slate-200 text-slate-400')">
                            <span x-show="!isStepSelected(step.id)" x-text="index + 1"></span>
                            <svg x-show="isStepSelected(step.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none mb-1" x-text="step.name"></p>
                            <p class="font-bold text-slate-800 truncate" x-text="getSelectionName(step.id) || 'Not Selected'"></p>
                        </div>
                    </button>
                </div>
            </template>
        </div>

        {{-- 2. Component Selection (Main Area) --}}
        <div class="lg:col-span-3 space-y-6">
            
            {{-- Current Step Header --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 flex justify-between items-center">
                <div class="text-left">
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight" x-text="'Select ' + steps[currentStepIndex].name"></h2>
                    <p class="text-sm text-slate-400 font-medium mt-1">Showing compatible components based on your selections.</p>
                </div>
                {{-- Compatibility Badge --}}
                <div class="hidden md:block">
                    <span class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                        ✓ Compatibility Check Active
                    </span>
                </div>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="!loading">
                <template x-for="product in components" :key="product.id">
                    <div class="group bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 text-left flex flex-col">
                        <div class="aspect-square rounded-2xl overflow-hidden mb-4 bg-slate-50 border border-slate-100">
                            <img :src="product.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <p class="text-sky-500 text-[10px] font-black uppercase tracking-widest mb-1" x-text="product.brand"></p>
                        <h3 class="font-bold text-slate-800 text-sm h-10 overflow-hidden line-clamp-2" x-text="product.name"></h3>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <p class="font-black text-slate-900 text-lg tracking-tight" x-text="formatPrice(product.price)"></p>
                            <button @click="selectComponent(product)" 
                                    class="p-2.5 rounded-xl transition-all shadow-lg active:scale-90"
                                    :class="isSelected(product.id) ? 'bg-emerald-500 text-white' : 'bg-slate-900 text-white hover:bg-sky-500'">
                                <svg x-show="!isSelected(product.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <svg x-show="isSelected(product.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Loading State --}}
            <div x-show="loading" class="py-20 text-center">
                <div class="inline-block w-12 h-12 border-4 border-sky-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-4 font-black text-slate-400 uppercase tracking-widest text-xs">Scanning Inventory...</p>
            </div>

            {{-- Empty State --}}
            <div x-show="!loading && components.length === 0" class="py-20 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 text-center">
                <p class="text-slate-400 font-bold uppercase tracking-widest">No compatible components found.</p>
                <button @click="resetFilters()" class="mt-4 text-sky-500 font-black hover:underline">Reset Build &larr;</button>
            </div>
        </div>
    </div>
</div>

<script>
function pcBuilder() {
    return {
        steps: @json($steps),
        currentStepIndex: 0,
        components: [],
        selectedComponents: [],
        loading: false,
        
        // Compatibility state
        selectedSocket: null,
        selectedRamType: null,

        init() {
            this.fetchComponents();
        },

        fetchComponents() {
            this.loading = true;
            const step = this.steps[this.currentStepIndex];
            
            let url = `/pc-builder/components?category=${step.slug}`;
            if (this.selectedSocket) url += `&socket=${this.selectedSocket}`;
            if (this.selectedRamType) url += `&ram=${this.selectedRamType}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.components = data;
                    this.loading = false;
                });
        },

        selectComponent(product) {
            const stepId = this.steps[this.currentStepIndex].id;
            
            // Remove existing selection for this step if any
            this.selectedComponents = this.selectedComponents.filter(c => c.stepId !== stepId);
            
            // Add new selection
            this.selectedComponents.push({
                stepId: stepId,
                productId: product.id,
                name: product.name,
                price: product.price
            });

            // Update compatibility filters
            if (stepId === 'cpu') {
                this.selectedSocket = product.socket_type;
            } else if (stepId === 'mobo') {
                this.selectedSocket = product.socket_type;
                this.selectedRamType = product.ram_type;
            }

            // Move to next step automatically
            if (this.currentStepIndex < this.steps.length - 1) {
                this.currentStepIndex++;
                this.fetchComponents();
            }
        },

        isSelected(productId) {
            return this.selectedComponents.some(c => c.productId === productId);
        },

        isStepSelected(stepId) {
            return this.selectedComponents.some(c => c.stepId === stepId);
        },

        getSelectionName(stepId) {
            const found = this.selectedComponents.find(c => c.stepId === stepId);
            return found ? found.name : null;
        },

        get totalPrice() {
            return this.selectedComponents.reduce((sum, c) => sum + c.price, 0);
        },

        resetFilters() {
            this.selectedComponents = [];
            this.selectedSocket = null;
            this.selectedRamType = null;
            this.currentStepIndex = 0;
            this.fetchComponents();
        },

        finishBuild() {
            const ids = this.selectedComponents.map(c => c.productId);
            
            fetch('/pc-builder/add-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ products: ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                }
            });
        },

        formatPrice(n) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
        }
    }
}
</script>
@endsection
