<nav class="bg-white/80 backdrop-blur-md sticky top-4 z-40 border border-slate-200/50 rounded-[2rem] mb-10 shadow-sm mx-auto max-w-7xl">
    <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
        {{-- Menu Utama --}}
        <div class="flex items-center space-x-1 overflow-x-auto no-scrollbar w-full md:w-auto">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-500 hover:text-sky-500 hover:bg-sky-50' }}">
                Dashboard
            </a>
            
            <a href="{{ route('admin.products.index') }}" 
               class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-500 hover:text-sky-500 hover:bg-sky-50' }}">
                Manage Products
            </a>
            
            <a href="{{ route('admin.categories.index') }}" 
               class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-500 hover:text-sky-500 hover:bg-sky-50' }}">
                Manage Categories
            </a>
            
            <a href="{{ route('admin.orders.index') }}" 
               class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'text-slate-500 hover:text-sky-500 hover:bg-sky-50' }}">
                Manage Orders
            </a>
        </div>

        {{-- Status Indikator --}}
        <div class="hidden md:flex items-center space-x-4">
            <div class="flex flex-col items-end">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Admin Mode</span>
                <span class="text-xs font-bold text-emerald-500 flex items-center">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                    System Active
                </span>
            </div>
            <div class="h-8 w-px bg-slate-200"></div>
            <a href="/" class="text-slate-400 hover:text-sky-500 transition-colors" title="View Storefront">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </a>
        </div>
    </div>
</nav>