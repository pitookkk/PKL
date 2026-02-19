@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- User Profile Info --}}
    <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-[2rem] shadow-md">
            <h2 class="text-2xl font-bold mb-4">My Profile</h2>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            {{-- Link to edit profile --}}
        </div>
    </div>
    
    {{-- Order History --}}
    <div class="md:col-span-2">
        <div class="bg-white p-6 rounded-[2rem] shadow-md">
            <h2 class="text-2xl font-bold mb-4">My Order History</h2>
            <div class="space-y-4">
                @forelse ($orders as $order)
                    <div class="border rounded-[2rem] p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold">Order #{{ $order->id }}</p>
                                <p class="text-sm text-slate-500">{{ $order->order_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('completed') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                    @endswitch">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 font-bold">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        {{-- TODO: Add a button to view order details --}}
                    </div>
                @empty
                    <p class="text-slate-500">You haven't placed any orders yet.</p>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
