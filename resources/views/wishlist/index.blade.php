@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-10">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">My Wishlist</h1>
        <p class="text-slate-500 mt-2">Products you love, all in one place.</p>
    </div>

    @if($wishlistItems->isEmpty())
        <div class="text-center py-24 bg-white rounded-2xl border border-slate-200">
            <div class="text-6xl mb-4">🤍</div>
            <p class="text-xl text-slate-500 font-bold">Your wishlist is empty.</p>
            <p class="text-slate-400 mt-2">Browse our products to find something you love!</p>
            <a href="{{ route('products.index') }}" class="mt-6 inline-block bg-sky-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-sky-600 transition-all">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($wishlistItems as $item)
                @php($product = $item->product)
                <div class="group bg-white rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 flex flex-col">
                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="relative overflow-hidden rounded-xl mb-4 aspect-square">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg group-hover:text-sky-600 transition-colors truncate mb-2">
                            {{ $product->name }}
                        </h3>
                    </a>

                    <div class="mt-auto">
                        {{-- Price --}}
                        <div class="mb-4">
                            @if ($product->is_flash_sale_active)
                                <p class="text-slate-400 text-xs line-through">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                <p class="font-black text-rose-500 text-xl tracking-tight">Rp {{ number_format($product->current_price, 0, ',', '.') }}</p>
                            @else
                                <p class="font-black text-slate-900 text-xl tracking-tight">
                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                </p>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="space-y-2">
                             <a href="{{ route('products.show', $product->slug) }}" class="block w-full text-center bg-slate-900 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-sky-500 transition-colors">
                                Add to Cart
                            </a>
                            <form action="{{ route('wishlist.remove', $product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-center bg-red-100 text-red-600 py-2.5 rounded-lg font-semibold text-sm hover:bg-red-200 transition-colors">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
