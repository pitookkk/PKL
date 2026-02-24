@extends('layouts.app')

@section('title', 'Bundle Management')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-10 text-left">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Package Bundling</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage your special discount packages and product sets.</p>
        </div>
        <a href="{{ route('admin.bundles.create') }}" class="bg-slate-900 text-white font-black px-6 py-3 rounded-2xl shadow-xl hover:bg-sky-500 transition-all active:scale-95">
            + Create New Bundle
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-8 rounded-2xl">
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Bundle Info</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Products</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Price</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($bundles as $bundle)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap text-left flex items-center">
                                @if($bundle->image_path)
                                    <img src="{{ asset('storage/' . $bundle->image_path) }}" class="w-12 h-12 object-cover rounded-xl mr-4 shadow-sm border border-slate-100">
                                @else
                                    <div class="w-12 h-12 bg-slate-100 rounded-xl mr-4 flex items-center justify-center text-slate-400">📦</div>
                                @endif
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $bundle->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Created: {{ $bundle->created_at->format('d M Y') }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-sm font-bold text-slate-700">{{ $bundle->products_count }} Items</div>
                                <div class="text-[10px] text-slate-400 font-medium">Included in this set</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left text-sm font-black text-sky-600">
                                Rp {{ number_format($bundle->price, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest {{ $bundle->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $bundle->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.bundles.edit', $bundle) }}" class="text-xs font-black text-sky-500 hover:text-sky-700 uppercase tracking-widest transition-colors">Edit</a>
                                    <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" class="inline" onsubmit="return confirm('Hapus paket bundling ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-medium italic">Belum ada paket bundling yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $bundles->links() }}
    </div>
</div>
@endsection
