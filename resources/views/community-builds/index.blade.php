@extends('layouts.app')

@section('title', 'Community Builds Gallery - Pitocom Showcase')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    
    {{-- Header --}}
    <div class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6 text-left">
        <div class="animate-fade-in-up">
            <h1 class="text-5xl font-black text-slate-900 tracking-tight">Community <span class="text-sky-500">Builds</span></h1>
            <p class="text-slate-500 mt-2 font-medium">Inspirasi rakitan PC dari komunitas Pitocom.</p>
        </div>
        @auth
            <a href="{{ route('community-builds.create') }}" class="bg-slate-900 text-white font-black px-8 py-4 rounded-2xl shadow-xl hover:bg-sky-500 transition-all active:scale-95">
                Share Your Build
            </a>
        @endauth
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-2xl">
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Gallery Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($builds as $build)
            <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col text-left">
                {{-- Build Image --}}
                <div class="relative aspect-video overflow-hidden">
                    <img src="{{ asset('storage/' . $build->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                        <p class="text-white text-sm font-medium italic">"{{ Str::limit($build->description, 100) }}"</p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-black text-slate-900 leading-tight">{{ $build->title }}</h3>
                        <div class="flex items-center text-rose-500">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                            <span class="ml-1 text-xs font-bold">{{ $build->likes_count }}</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-sky-100 text-sky-600 rounded-lg flex items-center justify-center font-black text-[10px]">
                            {{ substr($build->user->name, 0, 1) }}
                        </div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Built by <span class="text-slate-900">{{ $build->user->name }}</span></p>
                    </div>

                    <div class="mt-auto">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Linked Parts:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($build->products->take(3) as $product)
                                <span class="px-3 py-1 bg-slate-50 text-slate-600 rounded-lg text-[10px] font-bold border border-slate-100">
                                    {{ $product->name }}
                                </span>
                            @endforeach
                            @if($build->products->count() > 3)
                                <span class="px-3 py-1 bg-slate-50 text-slate-400 rounded-lg text-[10px] font-bold">+{{ $build->products->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <p class="text-slate-400 font-bold uppercase tracking-widest italic">No builds shared yet. Be the first!</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-12">
        {{ $builds->links() }}
    </div>
</div>
@endsection
