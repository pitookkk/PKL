@extends('layouts.app')

@section('title', 'Community Build Moderation')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="mb-10 text-left">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Showcase Moderation</h1>
        <p class="text-slate-500 mt-1 font-medium">Review dan publikasikan rakitan PC pilihan komunitas.</p>
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
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Build Info</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Submitted By</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($builds as $build)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6 whitespace-nowrap text-left flex items-center">
                                <img src="{{ asset('storage/' . $build->image_path) }}" class="w-16 h-10 object-cover rounded-lg mr-4 shadow-sm border border-slate-100">
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $build->title }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $build->created_at->format('d M, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <div class="text-sm font-semibold text-slate-700">{{ $build->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $build->user->email }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-left">
                                <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest
                                    @switch($build->status)
                                        @case('approved') bg-emerald-100 text-emerald-600 @break
                                        @case('rejected') bg-rose-100 text-rose-500 @break
                                        @default bg-amber-100 text-amber-600 @break
                                    @endswitch">
                                    {{ $build->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right">
                                <div class="flex justify-end space-x-3">
                                    @if($build->status !== 'approved')
                                        <form action="{{ route('admin.community-builds.approve', $build) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-black text-emerald-500 hover:text-emerald-700 uppercase tracking-widest transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($build->status !== 'rejected')
                                        <form action="{{ route('admin.community-builds.reject', $build) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest transition-colors">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-16 text-center text-slate-400 font-medium italic">Belum ada postingan komunitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $builds->links() }}
    </div>
</div>
@endsection
