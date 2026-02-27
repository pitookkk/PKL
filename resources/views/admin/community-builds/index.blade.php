@extends('layouts.app')

@section('title', 'Showcase Moderation - Pitocom Admin')

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    /* Animasi Section Reveal */
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        processedIds: [],
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        },
        submitAction(id, formId) {
            this.processedIds.push(id);
            setTimeout(() => {
                document.getElementById(formId).submit();
            }, 400);
        }
     }" x-init="initObserver()">
    
    {{-- Header Section --}}
    <div class="reveal-section mb-12 text-left">
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase text-left">Showcase <span class="text-sky-500">Moderation.</span></h1>
        <p class="text-slate-400 mt-2 font-bold text-xs uppercase tracking-widest pl-1 text-left">Review dan publikasikan rakitan PC pilihan komunitas.</p>
        <div class="h-1 w-20 bg-sky-500 mt-5 rounded-full text-left"></div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="reveal-section bg-emerald-50 border border-emerald-100 text-emerald-600 p-5 mb-8 rounded-[1.5rem] flex items-center shadow-sm text-xs font-black uppercase tracking-widest">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Section --}}
    <div class="reveal-section bg-white shadow-sm rounded-[3.5rem] overflow-hidden border border-slate-100 mb-10">
        <div class="overflow-x-auto no-scrollbar">
            <table class="min-w-full divide-y divide-slate-50 table-fixed">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="w-80 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Build Identity</th>
                        <th class="w-64 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Submitted By</th>
                        <th class="w-40 px-10 py-7 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Current Status</th>
                        <th class="w-48 px-10 py-7 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Decision Hub</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($builds as $build)
                        <tr x-show="!processedIds.includes({{ $build->id }})"
                            x-transition:leave="transition ease-in duration-400 transform"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95 translate-x-12"
                            class="hover:bg-slate-50/30 transition-all group">
                            
                            {{-- Build Info --}}
                            <td class="px-10 py-6">
                                <div class="flex items-center text-left">
                                    <div class="w-20 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner group-hover:rotate-2 transition-transform duration-500 mr-5 shrink-0">
                                        <img src="{{ asset('storage/' . $build->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate text-left">
                                        <div class="text-sm font-black text-slate-900 tracking-tight truncate text-left" title="{{ $build->title }}">{{ $build->title }}</div>
                                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1 text-left">{{ $build->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Submitter --}}
                            <td class="px-10 py-6 text-left">
                                <div class="text-sm font-black text-slate-700 text-left">{{ $build->user->name }}</div>
                                <div class="text-[10px] text-indigo-500 font-bold tracking-tight text-left">{{ $build->user->email }}</div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-10 py-6 text-center">
                                @php
                                    $statusClass = match($build->status) {
                                        'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'rejected' => 'bg-rose-50 text-rose-500 border-rose-100',
                                        default => 'bg-amber-50 text-amber-600 border-amber-100',
                                    };
                                @endphp
                                <span class="inline-flex px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border shadow-sm {{ $statusClass }}">
                                    {{ $build->status }}
                                </span>
                            </td>

                            {{-- Decision Hub --}}
                            <td class="px-10 py-6 text-right">
                                <div class="flex justify-end items-center space-x-3 text-left">
                                    @if($build->status !== 'approved')
                                        <form id="approve-form-{{ $build->id }}" action="{{ route('admin.community-builds.approve', $build) }}" method="POST" class="m-0 text-left">
                                            @csrf
                                            <button type="button" @click="submitAction({{ $build->id }}, 'approve-form-{{ $build->id }}')"
                                                    class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest border border-emerald-100 transition-all active:scale-90">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    @if($build->status !== 'rejected')
                                        <form id="reject-form-{{ $build->id }}" action="{{ route('admin.community-builds.reject', $build) }}" method="POST" class="m-0 text-left">
                                            @csrf
                                            <button type="button" @click="submitAction({{ $build->id }}, 'reject-form-{{ $build->id }}')"
                                                    class="px-4 py-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest border border-rose-100 transition-all active:scale-90">
                                                Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-10 py-32 text-center text-slate-300 font-black uppercase tracking-widest text-xs">No community builds pending review.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="reveal-section flex justify-center text-left">{{ $builds->links() }}</div>
</div>
@endsection