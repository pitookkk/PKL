@extends('layouts.app')

@section('title', 'Bundle Hub - Pitocom Admin')

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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        } 
     }" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">Package <span class="text-sky-500">Bundling.</span></h1>
                <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1 text-left">Elite hardware sets management system</p>
                <div class="h-1 w-20 bg-sky-500 mt-5 rounded-full text-left"></div>
            </div>
            <a href="{{ route('admin.bundles.create') }}" class="bg-slate-900 text-white font-black px-10 py-5 rounded-[1.8rem] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95 text-xs uppercase tracking-widest shrink-0">
                + Create New Bundle
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="reveal-section bg-emerald-50 border border-emerald-100 text-emerald-600 p-5 mb-8 rounded-[1.5rem] flex items-center shadow-sm text-xs font-black uppercase tracking-widest text-left">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Section - No Dragging Scale --}}
    <div class="reveal-section bg-white shadow-sm rounded-[3rem] overflow-hidden border border-slate-100 mb-10">
        <div class="overflow-x-auto no-scrollbar text-left text-left">
            <table class="min-w-full divide-y divide-slate-50 table-fixed text-left text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="w-72 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Bundle Identity</th>
                        <th class="w-40 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Items</th>
                        <th class="w-48 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Bundle Price</th>
                        <th class="w-40 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="w-40 px-10 py-7 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($bundles as $bundle)
                        <tr class="hover:bg-slate-50/30 transition-all group">
                            {{-- Info --}}
                            <td class="px-10 py-6 text-left">
                                <div class="flex items-center text-left text-left">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shadow-inner group-hover:rotate-3 transition-all duration-500 mr-5 shrink-0 text-left">
                                        @if($bundle->image_path)
                                            <img src="{{ asset('storage/' . $bundle->image_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-xl grayscale opacity-50 text-left text-left">📦</div>
                                        @endif
                                    </div>
                                    <div class="truncate text-left text-left">
                                        <div class="text-sm font-black text-slate-900 tracking-tight truncate text-left text-left" title="{{ $bundle->name }}">{{ $bundle->name }}</div>
                                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1 text-left text-left text-left">ID: #BND-{{ $bundle->id }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Products Count --}}
                            <td class="px-10 py-6 whitespace-nowrap text-center text-left">
                                <div class="text-sm font-black text-slate-700 leading-none text-left text-left text-center">{{ $bundle->products_count }} Hardware</div>
                                <div class="text-[8px] text-slate-400 font-black uppercase tracking-tighter mt-1.5 text-center text-left text-left">Inclusion Set</div>
                            </td>

                            {{-- Price --}}
                            <td class="px-10 py-6 whitespace-nowrap text-center text-left text-left">
                                <p class="text-base font-black text-sky-600 tracking-tighter leading-none text-left text-left text-center">Rp{{ number_format($bundle->price, 0, ',', '.') }}</p>
                                <p class="text-[8px] text-slate-300 font-black uppercase tracking-widest mt-2 text-center text-left text-left">Package Total</p>
                            </td>

                            {{-- Status --}}
                            <td class="px-10 py-6 whitespace-nowrap text-center text-left text-left text-left">
                                @if($bundle->is_active)
                                    <span class="inline-flex px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm text-left">Public</span>
                                @else
                                    <span class="inline-flex px-4 py-1.5 rounded-full bg-slate-50 text-slate-300 text-[9px] font-black uppercase tracking-widest border border-slate-100 text-left text-left">Draft</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-10 py-6 whitespace-nowrap text-right text-left text-left text-left text-left">
                                <div class="flex justify-end items-center space-x-3 text-left">
                                    <a href="{{ route('admin.bundles.edit', $bundle) }}" 
                                       class="px-4 py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-[0.1em] hover:bg-sky-500 transition-all shadow-lg active:scale-90 text-left text-left text-left text-left text-left">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" class="m-0 text-left">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-rose-50 text-rose-400 hover:text-white hover:bg-rose-500 rounded-xl transition-all border border-rose-100 shadow-sm text-left text-left" 
                                                onclick="return confirm('Hapus paket bundling ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-10 py-32 text-center text-slate-300 font-black uppercase tracking-widest text-xs">No active bundles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="reveal-section flex justify-center text-left text-left text-left text-left">{{ $bundles->links() }}</div>
</div>
@endsection