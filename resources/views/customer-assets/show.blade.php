@extends('layouts.app')

@section('title', 'Asset Details: ' . $customerAsset->device_name)

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $customerAsset->device_name }}</h1>
        
        <div class="border-t border-gray-200 pt-4">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Owner</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $customerAsset->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                    <dd class="mt-1 text-lg text-gray-900">{{ $customerAsset->purchase_date->format('d F, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Warranty Status</dt>
                    @if ($customerAsset->warranty_expiry)
                        <dd class="mt-1 text-lg font-semibold {{ $customerAsset->warranty_expiry->isFuture() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $customerAsset->warranty_expiry->isFuture() ? 'Active' : 'Expired' }}
                            <span class="block text-sm font-normal text-gray-500">(Expires: {{ $customerAsset->warranty_expiry->format('d F, Y') }})</span>
                        </dd>
                    @else
                        <dd class="mt-1 text-lg text-gray-700">Not Applicable</dd>
                    @endif
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Specifications</dt>
                    <dd class="mt-1 text-gray-900">
                        <pre class="bg-gray-100 p-4 rounded-md text-sm"><code>{{ json_encode($customerAsset->specifications, JSON_PRETTY_PRINT) }}</code></pre>
                    </dd>
                </div>
                 <div class="sm:col-span-2 text-center mt-4">
                    <p class="text-xs text-gray-500">Asset ID: {{ $customerAsset->id }}</p>
                 </div>
            </dl>
        </div>
    </div>
</div>
@endsection
