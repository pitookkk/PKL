<nav class="bg-white/80 backdrop-blur-xl sticky top-4 z-40 border border-slate-200/50 rounded-[2rem] mb-10 shadow-sm mx-auto max-w-[1600px] transition-all duration-500">
    <div class="px-6 py-3 flex flex-row items-center justify-between gap-4">
        
        {{-- Menu Utama - Dibuat lebih ringkas dengan flex-wrap agar adaptif --}}
        <div class="flex items-center flex-wrap lg:flex-nowrap gap-1 min-w-0 overflow-hidden">
            {{-- Nav Item Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Dash
            </a>

            {{-- Nav Item User --}}
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Users
            </a>
            
            {{-- Nav Item Products --}}
            <a href="{{ route('admin.products.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Products
            </a>

            {{-- Nav Item Flash Sales --}}
            <a href="{{ route('admin.flash-sales.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.flash-sales.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Sales
            </a>

            {{-- Nav Item Bundle --}}
            <a href="{{ route('admin.bundles.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.bundles.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Bundles
            </a>

            {{-- Nav Item Vouchers --}}
            <a href="{{ route('admin.vouchers.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.vouchers.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Vouchers
            </a>
            
            {{-- Nav Item Categories --}}
            <a href="{{ route('admin.categories.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Categories
            </a>
            
            {{-- Nav Item Orders --}}
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Orders
            </a>

            {{-- Nav Item Reviews --}}
            <a href="{{ route('admin.reviews.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.reviews.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Reviews
            </a>

            {{-- Nav Item Build Moderation --}}
            <a href="{{ route('admin.community-builds.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.community-builds.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               Moderation
            </a>

            {{-- Nav Item CRM --}}
            <a href="{{ route('admin.crm.index') }}" 
               class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 {{ request()->routeIs('admin.crm.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-400 hover:text-sky-500 hover:bg-sky-50' }}">
               CRM
            </a>
        </div>

        {{-- Status Indikator - Sisi Kanan yang Lebih Ringkas --}}
        <div class="flex items-center space-x-4 shrink-0">
            <div class="hidden xl:flex flex-col items-end leading-none">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">System</span>
                <span class="text-[10px] font-black text-emerald-500 flex items-center uppercase">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                    Active
                </span>
            </div>
            <div class="h-8 w-px bg-slate-100"></div>
            <a href="/" class="p-2.5 bg-slate-50 text-slate-400 hover:text-sky-500 hover:bg-white hover:shadow-md rounded-xl transition-all" title="View Storefront">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </a>
        </div>
    </div>
</nav>