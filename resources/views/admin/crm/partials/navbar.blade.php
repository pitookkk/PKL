<div class="reveal-section mb-10 bg-white/80 backdrop-blur-md shadow-sm rounded-[2rem] border border-slate-100 p-2.5 sticky top-5 z-40 transition-all duration-500 hover:shadow-xl hover:shadow-sky-500/5">
    <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-2 gap-4">
        
        {{-- Back Hub --}}
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-all">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Return to Dash
            </a>
            <span class="hidden sm:block h-4 w-px bg-slate-100 mx-8"></span>
        </div>

        {{-- Main CRM Links --}}
        <div class="flex items-center space-x-2 md:space-x-4">
            {{-- Customers Link --}}
            <a href="{{ route('admin.crm.index') }}" 
               class="relative px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 group
               {{ request()->routeIs('admin.crm.index*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600' }}">
                <span class="relative z-10 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Intelligence
                </span>
                @if(request()->routeIs('admin.crm.index*'))
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-sky-500 rounded-full shadow-[0_0_8px_#0ea5e9]"></div>
                @endif
            </a>

            {{-- Tickets Link --}}
            <a href="{{ route('admin.crm.tickets-board') }}" 
               class="relative px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 group
               {{ request()->routeIs('admin.crm.tickets-board') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600' }}">
                <span class="relative z-10 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Service Board
                </span>
                @if(request()->routeIs('admin.crm.tickets-board'))
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-sky-500 rounded-full shadow-[0_0_8px_#0ea5e9]"></div>
                @endif
            </a>

            {{-- Broadcast Link --}}
            <a href="{{ route('admin.crm.broadcast.form') }}" 
               class="relative px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 group
               {{ request()->routeIs('admin.crm.broadcast.form') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600' }}">
                <span class="relative z-10 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Broadcast Engine
                </span>
                @if(request()->routeIs('admin.crm.broadcast.form'))
                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-sky-500 rounded-full shadow-[0_0_8px_#0ea5e9]"></div>
                @endif
            </a>
        </div>

        {{-- Status Indicator --}}
        <div class="hidden lg:flex items-center bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 shadow-inner">
            <div class="w-2 h-2 rounded-full bg-emerald-500 mr-3 animate-pulse"></div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">CRM Active</p>
        </div>
    </div>
</div>