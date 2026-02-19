@extends('layouts.app')

@section('title', 'All Products - Pitocom')

@section('content')
    <h1 class="text-3xl font-bold mb-8">Our Products</h1>

    {{-- TODO: Add Filters (Category, Brand, Price) --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @forelse ($products as $product)
            <div class="bg-white rounded-[2rem] shadow-md overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                <a href="{{ route('products.show', $product) }}">
                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-lg truncate">{{ $product->name }}</h3>
                        <p class="text-slate-500 text-sm">{{ $product->category->name }}</p>
                        <p class="mt-2 font-bold text-sky-600 text-xl">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <p class="text-xl text-slate-500">No products found.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-sky-600 hover:underline">Clear Search</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-12">
        {{ $products->links() }}
    </div>
@endsection
