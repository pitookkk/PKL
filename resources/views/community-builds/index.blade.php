@extends('layouts.app')

@section('title', 'Community Builds Gallery - Pitocom Showcase')

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
    <div class="reveal-section mb-12 flex flex-col md:flex-row justify-between items-center gap-8 text-left">
        <div class="text-left">
            <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase">Community <span class="text-sky-500">Builds.</span></h1>
            <p class="text-slate-500 mt-2 font-medium text-lg italic">Inspirasi rakitan PC performa tinggi dari komunitas setia Pitocom.</p>
            <div class="h-1.5 w-24 bg-sky-500 mt-5 rounded-full text-left"></div>
        </div>
        @auth
            <a href="{{ route('community-builds.create') }}" class="bg-slate-900 text-white font-black px-10 py-5 rounded-[2rem] shadow-2xl shadow-slate-200 hover:bg-sky-500 transition-all active:scale-95 text-xs uppercase tracking-widest flex items-center group">
                <svg class="w-4 h-4 mr-3 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Share Your Build
            </a>
        @endauth
    </div>

    @if(session('success'))
        <div class="reveal-section bg-emerald-50 border border-emerald-100 text-emerald-600 p-6 mb-10 rounded-[2rem] flex items-center shadow-sm text-xs font-black uppercase tracking-widest">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Gallery Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($builds as $index => $build)
            <div class="reveal-section group bg-white rounded-[3.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-700 border border-slate-100 flex flex-col text-left"
                 style="--delay: {{ $index * 100 }}ms; transition-delay: var(--delay);">
                
                {{-- Build Image with Gradient Overlay --}}
                <div class="relative aspect-video overflow-hidden bg-slate-100 shadow-inner">
                    <img src="{{ asset('storage/' . $build->image_path) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-end p-8">
                        <p class="text-white text-sm font-medium italic leading-relaxed translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            "{{ Str::limit($build->description, 100) }}"
                        </p>
                    </div>
                </div>

                {{-- Content Hub --}}
                <div class="p-10 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-6 text-left">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter leading-none group-hover:text-sky-500 transition-colors">{{ $build->title }}</h3>
                        <div class="flex items-center bg-rose-50 px-3 py-1.5 rounded-full border border-rose-100 text-rose-500 shadow-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            <span class="ml-1.5 text-[10px] font-black tracking-tighter">{{ number_format($build->likes_count) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mb-8 text-left">
                        <div class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black text-xs shadow-lg group-hover:bg-sky-500 transition-colors">
                            {{ substr($build->user->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] leading-none mb-1">Architect</p>
                            <p class="text-sm font-black text-slate-700 tracking-tight">{{ $build->user->name }}</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-8 border-t border-slate-50 text-left">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-4">Invoiced Hardware:</p>
                        <div class="flex flex-wrap gap-2 text-left">
                            @foreach($build->products->take(3) as $product)
                                <span class="px-4 py-1.5 bg-slate-50 text-slate-500 rounded-xl text-[9px] font-black uppercase tracking-widest border border-slate-100 shadow-inner group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors">
                                    {{ $product->name }}
                                </span>
                            @endforeach
                            @if($build->products->count() > 3)
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-[9px] font-black">+{{ $build->products->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center reveal-section">
                <div class="text-6xl mb-6 grayscale opacity-20">🖥️</div>
                <p class="text-slate-400 font-black uppercase tracking-[0.3em] text-xs">No community masterpieces shared yet.</p>
                <a href="{{ route('community-builds.create') }}" class="mt-6 inline-block text-sky-500 font-black hover:underline uppercase text-[10px] tracking-widest">Be the pioneer architect &rarr;</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination Styling --}}
    <div class="mt-20 reveal-section flex justify-center text-left">
        {{ $builds->links() }}
    </div>
</div>
@endsection