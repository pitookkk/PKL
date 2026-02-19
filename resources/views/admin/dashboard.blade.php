@extends('layouts.app') {{-- For simplicity, we reuse the main layout. A dedicated admin layout would be better. --}}

@section('title', 'Admin Dashboard')

@section('content')
    @include('admin.partials.nav')

    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
    
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] shadow">
            <h3 class="text-slate-500">Total Products</h3>
            <p class="text-3xl font-bold">{{ $stats['products_count'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow">
            <h3 class="text-slate-500">Total Categories</h3>
            <p class="text-3xl font-bold">{{ $stats['categories_count'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow">
            <h3 class="text-slate-500">Total Orders</h3>
            <p class="text-3xl font-bold">{{ $stats['orders_count'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow">
            <h3 class="text-slate-500">Total Users</h3>
            <p class="text-3xl font-bold">{{ $stats['users_count'] }}</p>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white p-6 rounded-[2rem] shadow">
        <h2 class="text-2xl font-bold mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-2">Order ID</th>
                        <th class="p-2">Customer</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stats['recent_orders'] as $order)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="p-2">#{{ $order->id }}</td>
                            <td class="p-2">{{ $order->user->name }}</td>
                            <td class="p-2">{{ $order->order_date->format('d M Y') }}</td>
                            <td class="p-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('completed') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                    @endswitch">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="p-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="p-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-sky-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-500">No recent orders.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
