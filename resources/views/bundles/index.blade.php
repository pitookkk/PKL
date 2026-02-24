@extends('layouts.app')

@section('title', 'Paket Hemat Bundling - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    
    {{-- Header --}}
    <div class="mb-12 text-left">
        <h1 class="text-5xl font-black text-slate-900 tracking-tight">Paket <span class="text-indigo-600">Hemat</span></h1>
        <p class="text-slate-500 mt-2 font-medium">Dapatkan harga lebih murah dengan membeli produk dalam satu paket rakitan.</p>
    </div>

    {{-- Bundle Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        @forelse($bundles as $bundle)
            <div class="group bg-white rounded-[3rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col md:flex-row text-left">
                
                {{-- Bundle Image --}}
                <div class="md:w-2/5 relative overflow-hidden">
                    <img src="{{ $bundle->image_path ? asset('storage/' . $bundle->image_path) : 'https://via.placeholder.com/400' }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute top-6 left-6">
                        <span class="bg-indigo-600 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Special Deal</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="md:w-3/5 p-8 md:p-10 flex flex-col">
                    <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $bundle->name }}</h3>
                    <p class="text-slate-500 text-sm mb-6 leading-relaxed">{{ $bundle->description }}</p>

                    <div class="space-y-3 mb-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Apa yang kamu dapatkan:</p>
                        @foreach($bundle->products as $product)
                            <div class="flex items-center text-sm font-bold text-slate-700">
                                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                {{ $product->pivot->quantity }}x {{ $product->name }}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Bundle Price</p>
                            <p class="text-3xl font-black text-indigo-600 tracking-tighter">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>
                        </div>
                        <form action="{{ route('cart.add', ['product' => $bundle->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="bundle">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-slate-900 text-white p-4 rounded-2xl hover:bg-indigo-600 transition-all shadow-xl active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <p class="text-slate-400 font-bold uppercase tracking-widest italic">Belum ada paket hemat tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
