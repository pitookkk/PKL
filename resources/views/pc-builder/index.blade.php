@extends('layouts.app')

@section('title', 'PC Builder - Pitocom Virtual Assembly')

@section('content')


<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible { opacity: 1; transform: translateY(0); }

    /* Animasi Shimmer untuk Loading instant */
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="pcBuilder()" 
     x-init="initObserver()"
     data-steps="{{ json_encode($steps) }}">
    
    {{-- Header & Totalizer Hub --}}
    <div class="reveal-section mb-12 flex flex-col lg:flex-row justify-between items-center gap-8 text-left">
        <div class="text-left">
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase">PC <span class="text-sky-500">Builder.</span></h1>
            <p class="text-slate-500 mt-2 font-medium text-lg italic">Rakit PC impianmu dengan sistem validasi kompatibilitas otomatis.</p>
            <div class="h-1.5 w-24 bg-sky-500 mt-5 rounded-full text-left"></div>
        </div>

        <div class="bg-slate-900 p-8 rounded-[3rem] shadow-2xl flex items-center space-x-10 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-sky-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform"></div>
            <div class="text-left relative">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mb-1.5">Estimated Price</p>
                <p class="text-4xl font-black text-sky-400 tracking-tighter" x-text="formatPrice(totalPrice)"></p>
            </div>
            <button @click="finishBuild()" 
                    class="relative bg-sky-500 hover:bg-white hover:text-slate-900 text-white font-black px-10 py-5 rounded-2xl transition-all shadow-xl active:scale-95 disabled:bg-slate-800 disabled:text-slate-600 uppercase text-[10px] tracking-widest flex items-center"
                    :disabled="selectedComponents.length === 0">
                Finalize Build
                <svg class="w-4 h-4 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
        
        {{-- 1. Step Navigation Hub (Sidebar) --}}
        <div class="lg:col-span-1 space-y-4">
            <template x-for="(step, index) in steps" :key="step.id">
                <div class="reveal-section" :style="'transition-delay: ' + (index * 50) + 'ms'">
                    <button @click="changeStep(index)"
                            class="w-full flex items-center p-6 rounded-[2rem] transition-all duration-500 text-left group relative border border-transparent"
                            :class="currentStepIndex === index ? 'bg-white shadow-xl shadow-slate-200 border-slate-100' : 'hover:bg-white/50'">
                        
                        <div x-show="currentStepIndex === index" class="absolute left-0 top-0 bottom-0 w-2 bg-sky-500 rounded-r-full"></div>

                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm mr-5 shadow-inner"
                             :class="isStepSelected(step.id) ? 'bg-emerald-500 text-white' : (currentStepIndex === index ? 'bg-slate-900 text-white scale-110' : 'bg-slate-100 text-slate-300')">
                            <span x-show="!isStepSelected(step.id)" x-text="index + 1"></span>
                            <svg x-show="isStepSelected(step.id)" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1" x-text="step.name"></p>
                            <p class="font-black text-slate-800 truncate text-sm" x-text="getSelectionName(step.id) || 'Ready'"></p>
                        </div>
                    </button>
                </div>
            </template>
        </div>

        {{-- 2. Component Engine (Main Area) --}}
        <div class="lg:col-span-3 space-y-8">
            
            <div class="reveal-section bg-white p-10 rounded-[3.5rem] border border-slate-100 flex flex-col md:flex-row justify-between items-center shadow-sm relative overflow-hidden text-left">
                <div class="text-left relative">
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter" x-text="'Select ' + steps[currentStepIndex].name"></h2>
                    <p class="text-slate-400 font-medium mt-1">Filtering inventory with <span class="text-emerald-500 font-bold">V-Check Logic</span>.</p>
                </div>
                
                <div class="hidden md:block">
                    <span class="px-6 py-2.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-100 shadow-inner flex items-center">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></div>
                        V-Check Logic Active
                    </span>
                </div>
            </div>

            {{-- Instant Skeleton Loading --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" x-show="loading">
                <template x-for="i in 6">
                    <div class="bg-white p-6 rounded-[2.5rem] border border-slate-50 space-y-4">
                        <div class="aspect-square rounded-[1.8rem] skeleton"></div>
                        <div class="h-4 w-1/2 rounded skeleton"></div>
                        <div class="h-8 w-full rounded skeleton"></div>
                    </div>
                </template>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" x-show="!loading" x-cloak>
                <template x-for="(product, pIndex) in components" :key="product.id">
                    <div class="reveal-section group bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-700 flex flex-col text-left">
                        <div class="aspect-square rounded-[1.8rem] overflow-hidden mb-6 bg-slate-50 border border-slate-50 shadow-inner group-hover:rotate-2 transition-all duration-700">
                            <img :src="product.image" class="w-full h-full object-cover">
                        </div>
                        <div class="text-left flex-grow">
                            <p class="text-sky-500 text-[9px] font-black uppercase tracking-[0.2em] mb-1" x-text="product.brand"></p>
                            <h3 class="font-black text-slate-800 text-base leading-tight h-12 overflow-hidden line-clamp-2" x-text="product.name"></h3>
                        </div>
                        <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between text-left">
                            <div class="text-left">
                                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Asset Price</p>
                                <p class="font-black text-slate-900 text-xl tracking-tighter" x-text="formatPrice(product.price)"></p>
                            </div>
                            <button @click="selectComponent(product)" 
                                    class="p-4 rounded-2xl transition-all shadow-lg active:scale-90"
                                    :class="isSelected(product.id) ? 'bg-emerald-500 text-white' : 'bg-slate-900 text-white hover:bg-sky-500'">
                                <svg x-show="!isSelected(product.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                <svg x-show="isSelected(product.id)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function pcBuilder() {
    return {
        steps: JSON.parse(document.querySelector('[data-steps]').getAttribute('data-steps')),
        currentStepIndex: 0,
        components: [],
        selectedComponents: [],
        loading: true,
        selectedSocket: null,
        selectedRamType: null,

        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
            this.fetchComponents();
        },

        changeStep(index) {
            this.currentStepIndex = index;
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
                    this.$nextTick(() => { this.initObserver(); });
                });
        },

        selectComponent(product) {
            const stepId = this.steps[this.currentStepIndex].id;
            this.selectedComponents = this.selectedComponents.filter(c => c.stepId !== stepId);
            this.selectedComponents.push({ stepId: stepId, productId: product.id, name: product.name, price: product.price });

            if (stepId === 'cpu') this.selectedSocket = product.socket_type;
            else if (stepId === 'mobo') { 
                this.selectedSocket = product.socket_type; 
                this.selectedRamType = product.ram_type; 
            }

            if (this.currentStepIndex < this.steps.length - 1) {
                this.currentStepIndex++;
                this.fetchComponents();
            }
        },

        formatPrice(n) { return 'Rp' + new Intl.NumberFormat('id-ID').format(n); },
        isSelected(id) { return this.selectedComponents.some(c => c.productId === id); },
        isStepSelected(id) { return this.selectedComponents.some(c => c.stepId === id); },
        getSelectionName(id) {
            const found = this.selectedComponents.find(c => c.stepId === id);
            return found ? found.name : null;
        },
        get totalPrice() { return this.selectedComponents.reduce((sum, c) => sum + c.price, 0); },
        
        finishBuild() {
            const ids = this.selectedComponents.map(c => c.productId);
            fetch('/pc-builder/add-all', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ products: ids })
            }).then(res => res.json()).then(data => { if (data.success) window.location.href = data.redirect; });
        }
    }
}
</script>
@endsection