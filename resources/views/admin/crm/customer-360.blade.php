@extends('layouts.app')

@section('title', "Intelligence 360 - {$user->name} | Pitocom")

@section('content')
    {{-- Navbar Admin --}}
    @include('admin.partials.nav')

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    .reveal-section {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .reveal-section.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" x-data="{ initObserver() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
}}" x-init="initObserver()">

    {{-- CRM Navigation --}}
    <div class="reveal-section mb-10 text-left">
        @include('admin.crm.partials.navbar')
    </div>

    {{-- Profile Header Card --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-12 mb-10 relative overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-10">
            <div class="flex items-center text-left">
                <div class="h-24 w-24 rounded-[2.5rem] bg-slate-900 flex items-center justify-center font-black text-white text-4xl shadow-2xl shadow-slate-200 mr-8 shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter">{{ $user->name }}</h1>
                    <p class="text-slate-400 font-bold mt-1 text-lg tracking-tight">{{ $user->email }}</p>
                    <div class="mt-4 flex items-center gap-3 text-left">
                        <span class="bg-indigo-50 text-indigo-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-indigo-100 shadow-sm italic">
                            System Access: Joined {{ $user->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-10 lg:gap-16">
                <div class="text-left">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2 text-left">Total Assets Spent</p>
                    <p class="text-2xl font-black text-emerald-500 tracking-tighter leading-none text-left">Rp{{ number_format($user->orders->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
                <div class="text-left border-l border-slate-50 pl-10">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2 text-left">Loyalty Fuel</p>
                    <p class="text-2xl font-black text-sky-500 tracking-tighter leading-none text-left">{{ number_format($user->loyaltyPoint->points_balance ?? 0, 0, ',', '.') }} <span class="text-[10px] uppercase ml-1">Pts</span></p>
                </div>
                <div class="text-left border-l border-slate-50 pl-10">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mb-2 text-left">Logistics Success</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter leading-none text-left">{{ $user->orders->count() }} <span class="text-[10px] uppercase ml-1">Orders</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Interaction & Hardware Column --}}
        <div class="lg:col-span-2 space-y-12">
            
            {{-- Interaction Hub --}}
            <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-12 text-left">
                <div class="flex items-center mb-10 text-left">
                    <div class="bg-slate-900 p-3 rounded-2xl mr-5 shadow-lg text-white text-left">
                        <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <h2 class="text-xs font-black text-slate-900 tracking-[0.4em] uppercase text-left">Intelligence Interaction Logs</h2>
                </div>
                
                <form action="{{ route('admin.crm.logInteraction', $user) }}" method="POST" class="mb-12 bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100 shadow-inner group">
                    @csrf
                    <textarea name="note" rows="2" required 
                              class="w-full bg-transparent border-none focus:ring-0 font-bold text-slate-700 placeholder-slate-300 text-lg leading-relaxed text-left" 
                              placeholder="Catat log interaksi kritis..."></textarea>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-sky-500 transition-all shadow-xl active:scale-95 flex justify-center items-center">
                            Add Log Entry
                        </button>
                    </div>
                </form>

                <div class="space-y-8 relative ml-6">
                    <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-slate-100 -ml-6"></div>

                    @forelse ($user->interactionLogs->sortByDesc('created_at') as $index => $log)
                        <div class="relative pl-8 reveal-section" style="--delay: {{ $index * 100 }}ms; transition-delay: var(--delay);">
                            <div class="absolute left-0 top-2 w-3 h-3 rounded-full bg-sky-500 -ml-[2.2rem] border-4 border-white shadow-sm ring-4 ring-sky-50"></div>
                            
                            <div class="bg-slate-50/50 p-6 rounded-3xl border border-slate-50 hover:bg-white hover:border-sky-100 transition-all text-left">
                                <p class="text-slate-800 font-bold leading-relaxed text-base text-left">"{{ $log->note }}"</p>
                                <div class="flex items-center mt-4 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">
                                    <span class="text-sky-500 mr-2">{{ $log->admin->name ?? 'SYSTEM' }}</span>
                                    <span class="opacity-30">•</span>
                                    <span class="ml-2 italic">{{ $log->created_at->format('d M Y | H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-left">
                            <p class="text-slate-300 font-black uppercase tracking-widest text-xs text-left">No interaction history detected</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="reveal-section bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10 text-left">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-8 text-left">Hardware Library</h3>
                    <div class="space-y-4">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-50 shadow-inner group hover:border-sky-200 transition-all text-left">
                             <p class="text-base font-black text-slate-900 tracking-tight text-left">Gaming PC Custom Build</p>
                             <p class="text-[10px] font-black text-slate-400 uppercase mt-2 tracking-widest italic text-left">Asset SN: PITO-2026-X88</p>
                        </div>
                    </div>
                </div>
                
                <div class="reveal-section bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10 text-center">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-8 text-left">Support Engine</h3>
                    <div class="py-8 opacity-20 grayscale text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[10px] font-black text-slate-400 uppercase italic mt-4 tracking-widest">Clear Tickets</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order History Sticky Column --}}
        <div class="space-y-12 text-left">
            <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 p-10 text-left h-fit sticky top-10">
                <div class="flex items-center justify-between mb-10 text-left">
                    <h2 class="text-xs font-black text-slate-900 tracking-[0.4em] uppercase text-left">Logistics Success</h2>
                    <span class="bg-slate-900 text-white px-3 py-1 rounded-xl text-[10px] font-black shadow-lg">{{ $user->orders->count() }} Orders</span>
                </div>

                <div class="space-y-5 max-h-[750px] overflow-y-auto no-scrollbar pr-1 text-left">
                    @forelse ($user->orders as $order)
                        <div class="p-8 bg-slate-50/50 rounded-[2.5rem] border border-slate-50 group hover:bg-white hover:border-sky-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 text-left">
                            <div class="flex justify-between items-start mb-3 text-left text-left">
                                <p class="text-lg font-black text-slate-900 group-hover:text-sky-600 transition-colors text-left">#{{ $order->id }}</p>
                                <span class="px-3 py-1 text-[8px] font-black rounded-lg uppercase tracking-widest {{ $order->status == 'completed' ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest text-left">{{ $order->order_date->format('d M, Y') }}</p>
                            <div class="mt-6 border-t border-slate-100 pt-6 flex justify-between items-center text-left">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Invoiced Amount</span>
                                <p class="text-xl font-black text-slate-800 tracking-tighter">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                         <div class="py-20 text-center text-left">
                            <p class="text-slate-300 font-black uppercase tracking-widest text-xs text-left">No transactional history</p>
                         </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection