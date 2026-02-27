@extends('layouts.app')

@section('title', 'CRM Hub - Pitocom Admin')

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

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        show: false,
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        }
     }" x-init="initObserver(); setTimeout(() => show = true, 100)">
    
    {{-- CRM Header Hub --}}
    <div class="reveal-section mb-12">
        <div class="mb-10">
            @include('admin.crm.partials.navbar')
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 text-left">
            <div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter uppercase">Customer <span class="text-sky-500">Management.</span></h1>
                <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1">Kelola data pelanggan, pantau loyalitas, dan lihat riwayat belanja Pitocom.</p>
                <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
            </div>
            
            {{-- Search/Summary Stats (Optional visual) --}}
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center space-x-6">
                <div class="text-center px-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Database</p>
                    <p class="text-xl font-black text-slate-900">{{ number_format($customers->total()) }}</p>
                </div>
                <div class="w-px h-8 bg-slate-100"></div>
                <div class="text-center px-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Growth</p>
                    <p class="text-xl font-black text-emerald-500">+12%</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Console --}}
    <div class="reveal-section mb-10 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm" style="transition-delay: 150ms">
        <form action="{{ route('admin.crm.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end">
            <div class="md:col-span-4 text-left">
                <label for="level" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Membership Tier</label>
                <select name="level" id="level" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner appearance-none cursor-pointer">
                    <option value="">All Membership Levels</option>
                    <option value="Silver" @selected(isset($filters['level']) && $filters['level'] == 'Silver')>Silver Member</option>
                    <option value="Gold" @selected(isset($filters['level']) && $filters['level'] == 'Gold')>Gold Member</option>
                    <option value="Platinum" @selected(isset($filters['level']) && $filters['level'] == 'Platinum')>Platinum Member</option>
                </select>
            </div>
            
            <div class="md:col-span-4 text-left">
                <label for="sort_spent" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Spending Logic</label>
                <select name="sort_spent" id="sort_spent" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 shadow-inner appearance-none cursor-pointer">
                    <option value="">Default (Join Date)</option>
                    <option value="desc" @selected(isset($filters['sort_spent']) && $filters['sort_spent'] == 'desc')>Revenue: High to Low (Sultan)</option>
                    <option value="asc" @selected(isset($filters['sort_spent']) && $filters['sort_spent'] == 'asc')>Revenue: Low to High</option>
                </select>
            </div>

            <div class="md:col-span-4 flex gap-3">
                <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl shadow-slate-200 active:scale-95 text-xs">
                    Apply Analytics
                </button>
                <a href="{{ route('admin.crm.index') }}" class="px-8 py-4 bg-slate-100 text-slate-400 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-xs flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Main Table Section --}}
    <div class="reveal-section bg-white rounded-[3.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20" style="transition-delay: 300ms">
        <div class="overflow-x-auto no-scrollbar">
            <table class="min-w-full divide-y divide-slate-50 table-fixed">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="w-80 px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Customer Profile</th>
                        <th class="w-48 px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Membership</th>
                        <th class="w-56 px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Total Life-Time Spent</th>
                        <th class="w-48 px-10 py-7 text-left text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Loyalty Engine</th>
                        <th class="w-44 px-10 py-7 text-right text-[11px] font-black text-slate-400 uppercase tracking-[0.25em]">Intelligence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse ($customers as $index => $customer)
                        <tr class="hover:bg-slate-50/30 transition-all group"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            style="display: none; --row-delay: {{ $index * 50 }}ms; transition-delay: var(--row-delay);">
                            
                            {{-- Customer --}}
                            <td class="px-10 py-8 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-900 flex items-center justify-center font-black text-white text-lg shadow-lg group-hover:bg-sky-500 transition-colors mr-5 shrink-0">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div class="truncate">
                                        <div class="text-base font-black text-slate-900 tracking-tight">{{ $customer->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $customer->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Rank --}}
                            <td class="px-10 py-8 whitespace-nowrap">
                                @php
                                    $level = $customer->membership_level;
                                    $rankStyle = match($level) {
                                        'Platinum' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'Gold' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-slate-50 text-slate-400 border-slate-100',
                                    };
                                @endphp
                                <span class="inline-flex px-5 py-2 text-[9px] font-black rounded-full border {{ $rankStyle }} uppercase tracking-[0.15em] shadow-sm">
                                    {{ $level }}
                                </span>
                            </td>

                            {{-- Spending --}}
                            <td class="px-10 py-8 whitespace-nowrap text-left text-left">
                                <p class="text-lg font-black text-emerald-500 tracking-tighter leading-none">Rp{{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                                <p class="text-[8px] text-slate-300 font-black uppercase tracking-widest mt-2">Gross Revenue Assets</p>
                            </td>

                            {{-- Points --}}
                            <td class="px-10 py-8 whitespace-nowrap">
                                <div class="flex items-center text-left text-left">
                                    <span class="text-lg font-black text-slate-800 tracking-tighter mr-2">{{ number_format($customer->points, 0, ',', '.') }}</span>
                                    <div class="px-2 py-0.5 bg-slate-100 rounded text-[8px] font-black text-slate-400 uppercase">PTS</div>
                                </div>
                                <div class="w-24 bg-slate-50 h-1 rounded-full mt-2 overflow-hidden shadow-inner text-left">
                                    <div class="bg-sky-500 h-full w-2/3"></div>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-10 py-8 whitespace-nowrap text-right">
                                <a href="{{ route('admin.crm.customer360', $customer) }}" 
                                   class="inline-flex items-center px-6 py-3 bg-slate-900 text-white rounded-xl font-black text-[9px] uppercase tracking-[0.2em] hover:bg-sky-500 transition-all shadow-xl shadow-slate-100 active:scale-90">
                                    View 360&deg; Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-40 text-center bg-slate-50/20 text-left text-left text-left text-left">
                                <div class="text-6xl mb-6 grayscale opacity-20 text-left text-left text-left text-left text-left">👤</div>
                                <p class="text-slate-300 font-black uppercase tracking-[0.3em] text-xs text-left text-left text-left text-left text-left">Zero customer records detected</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-10 py-10 bg-slate-50/30 border-t border-slate-50 text-left text-left">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection